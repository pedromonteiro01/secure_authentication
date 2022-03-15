import json, os, requests, secrets, time
from flask import Flask, render_template, request, redirect, url_for
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.primitives import padding, hashes
app = Flask(__name__)

AUTH_COUNTER = 1
AUTH_SUCCESS = True
generated_challenge = None

@app.route('/')
def create():
    return render_template('create.html')

def generate_key(password):
    hash_algorithm = hashes.SHA256()

    password = password.encode()
    salt = os.urandom(16)
    kdf = PBKDF2HMAC(
        algorithm=hash_algorithm,
        length=32,
        salt=salt,
        iterations=100000,
        backend=default_backend(),
    )
    key = kdf.derive(password)

    # Now we cut the key down to be usable by a certain algorithm by picking random bytes
    # AES-128 uses a key with 128 bits so we only want the first 16 bytes
    key = key[:16]

    return key

def generate_hash(data):
    data = bytes(data, encoding="utf-8")
    digest = hashes.Hash(hashes.SHA256())
    digest.update(data)
    result = digest.finalize()
    return result

def encrypt_data(data):

    padder = padding.PKCS7(128).padder()
    padded_data = padder.update(data)
    padded_data += padder.finalize()
    
    key = UAP_KEY[:16]
    cipher = Cipher(algorithms.AES(key), modes.CBC(iv))
    encryptor = cipher.encryptor()
    ct = encryptor.update(padded_data) + encryptor.finalize()
    
    return ct

def decrypt_data(data):

    if iv == None:
        raise Exception("ERROR: IV doesn't exist.")
    else:
        mode = modes.CBC(iv)
    
    key = UAP_KEY[:16]
    cipher = Cipher(algorithms.AES(key), mode, backend=default_backend())
    decryptor = cipher.decryptor()
    ct = decryptor.update(data) + decryptor.finalize()
    
    unpadder = padding.PKCS7(128).unpadder()
    unpadded_data = unpadder.update(ct)
    unpadded_data += unpadder.finalize()
    
    return unpadded_data


@app.route('/', methods=['GET', 'POST'])
def create_post():
    '''Login password manager'''
    global UAP_PASS
    global UAP_KEY
    global iv
    global pw_hash

    if os.path.getsize("./keys/key.key") == 0:
        UAP_PASS = "password"
        hashed_pass = generate_hash(UAP_PASS)
        UAP_KEY = generate_key(UAP_PASS)
        iv = os.urandom(16)
        with open("./keys/pass.txt", "wb") as f:
            f.write(hashed_pass)
        with open("./keys/key.key", 'wb') as f:
            f.write(UAP_KEY)
        with open("./keys/cbc.iv", "wb") as f:
            f.write(iv)
    else:
        with open("./keys/key.key", "rb") as f:
            UAP_KEY = f.readline()
        with open("./keys/pass.txt", "rb") as f:
            UAP_PASS = f.readline()
        with open("./keys/cbc.iv", "rb") as f:
            iv = f.readline()

    if 'password-create' in request.form.keys():
        pw = request.form['password-create']
        pw_hash = generate_hash(pw)
 
        if (pw_hash==UAP_PASS):
            return redirect(url_for('base_get'))
        else:
            return render_template('create.html', data='Wrong Password!')

@app.route('/login/')
def login():
    if (pw_hash!=UAP_PASS):
        return redirect(url_for('create_post'))
    return render_template('login.html')

@app.route('/base', methods=['GET'])
def base_get():
    if (pw_hash!=UAP_PASS):
        return redirect(url_for('create_post'))
    total_bytes = os.path.getsize('encrypted.txt')
    with open('encrypted.txt', 'rb')as f:
        b = f.read(total_bytes)
    dec_dict = decrypt_data(b).decode("utf-8").strip()
    dic = json.loads(dec_dict)
    keys=dic.keys()
    return render_template('table.html', data=dic)

@app.route('/base', methods=['POST'])
def base_post():
    pw = request.form['login-password']
    username = request.form['login-username']
    total_bytes = os.path.getsize('encrypted.txt')
    if total_bytes != 0:
        with open('encrypted.txt', 'rb')as f:
            b = f.read(total_bytes)
        dec_dict = decrypt_data(b).decode("utf-8").strip()
        dic = json.loads(dec_dict)
    else:
        dic = {}
    if DNS not in dic.keys():
        dic[DNS] = [[username, pw]]
    else:
        dic[DNS].append([username, pw])
    result = json.dumps(dic).encode("utf-8")
    result = encrypt_data(result)
    with open('encrypted.txt','wb') as f:
        f.write(result)
    
    return redirect(url_for('base_get'))

@app.route('/start_auth/<string:dns>/<string:user>')
def start_auth(dns, user):
    global user_pass
    total_bytes = os.path.getsize('encrypted.txt')
    with open('encrypted.txt', 'rb')as f:
        b = f.read(total_bytes)
    dec_dict = decrypt_data(b).decode("utf-8").strip()
    dic = json.loads(dec_dict)
    for u in dic[dns]:
        if user in u:
            user_pass = u[1]
    res = requests.post('http://172.18.0.2/response.php', params={'user':user})
    if not AUTH_SUCCESS:
        return redirect(url_for('error'))
    elif AUTH_SUCCESS:
        s = 'http://172.18.0.2/logged_in.php?logged='
        s = s + user
        return redirect(s)
    return redirect(url_for('base_get'))

@app.route('/get-origin/', methods=['POST'])
def get_origin():
    global DNS
    DNS = request.form['dns']
    return "OK"

@app.route('/error')
def error():
    return render_template('error.html')

@app.route('/test')
def test():
    return redirect('http://172.18.0.2/login.php')

@app.route('/get_auth/', methods=['POST'])
def get_auth():
    global hashed, AUTH_COUNTER, AUTH_SUCCESS, generated_challenge
    if 'challenge' in request.form:
        # receive challenge
        hashed = request.form['challenge']
        hashed_pass = generate_hash(user_pass).hex()

        #calculate response
        generated_challenge = generate_hash(hashed_pass + hashed).hex()
        print(generated_challenge)
        #send first bit
        requests.post('http://172.18.0.2/response.php', params={'auth': generated_challenge[0]})
    elif 'auth' in request.form:
        auth = request.form['auth']
    #    bit = request.form['auth']
    #    print(f"{AUTH_COUNTER}:  server: {request.form['auth']} UAP: {generated_challenge[AUTH_COUNTER]}")
        if auth == "success" and AUTH_COUNTER == 11:
            AUTH_COUNTER=1
            AUTH_SUCCESS = True
        elif auth == 'failed':
            AUTH_COUNTER = 1
            AUTH_SUCCESS = False
        elif auth == generated_challenge[AUTH_COUNTER]:# se correto, enviar proximo byte
            AUTH_COUNTER += 2
            requests.post('http://172.18.0.2/response.php', params={'auth': generated_challenge[AUTH_COUNTER-1]})
        else: # se falso, enviar byte aleatorio
            AUTH_SUCCESS = False
            requests.post('http://172.18.0.2/response.php', params={'auth': secrets.token_bytes(1)})
    return "OK"
            
if __name__ == '__main__':
    app.run(debug=True, host='172.18.0.4')

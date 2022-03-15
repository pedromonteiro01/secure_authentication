FROM python:3.8-slim-buster
RUN pip install --upgrade pip


WORKDIR /home/rest_api
COPY . .

RUN pip install -r requirements.txt
CMD [ "python3", "-u", "api.py"]
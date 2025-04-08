# Docker

docker build --tag frontend:latest .
docker run -d -p 8091:80 frontend
docker push helsingborgstad/kokop:[tagname]

## ENV's

MS_AUTH        Auth service
MS_NAVET       Navet endpoint
MS_NAVET_AUTH  Navet auth key key
ENCRYPT_VECTOR Encryption vector
ENCRYPT_KEY    Encryption key
PREDIS         Redis connection details according to: <https://github.com/predis/predis> or a connection string. tcp://admin:password@127.0.0.1:6379

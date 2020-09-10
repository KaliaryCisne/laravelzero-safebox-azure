# BOILERPLATE

Modelo de script feito em LaravelZero, utilizando variáveis armazenadas em um cofre na Azure.

### Instalação - Criando a imagem
```
    docker-compose build --no-cache
```

### Executando a imagem [LOCAL]
```
    docker run --rm \   
    --device /dev/fuse \
    --privileged \ 
    --name script_laravelzero \      
    laravel-safeboz_application \
    ./run.sh
```

### Executar o container e entrar nele
```
    docker-compose run --rm application bash
```

### Executar o container 
```
    docker-compose run --rm application ./run.sh
```

# README do Projeto

Este README fornece instruções sobre como construir e executar um contêiner Docker com base no Dockerfile fornecido. O contêiner configura um servidor web Apache com PHP e MySQL, hospedando uma aplicação web a partir do diretório `app/` e inicializando um banco de dados MySQL com o script `init.sql`.

## Pré-requisitos

- **Docker**: Certifique-se de que o Docker está instalado no seu sistema. Você pode baixá-lo no [site oficial do Docker](https://www.docker.com/get-started).
- **Dockerfile**: O Dockerfile fornecido configura o ambiente.
- **Arquivos da Aplicação**: Certifique-se de ter o diretório `app/` com os arquivos da sua aplicação web.
- **Arquivo SQL**: Certifique-se de ter um arquivo `init.sql` para inicializar o banco de dados MySQL.
- **Script de Inicialização**: Certifique-se de ter um script `start.sh` para iniciar os serviços Apache e MySQL.

## Estrutura do Diretório

Seu diretório do projeto deve ter a seguinte estrutura:

```
project/
├── Dockerfile
├── app/
│   └── (seus arquivos da aplicação PHP)
├── init.sql
├── start.sh
└── README.md
```

## Visão Geral do Dockerfile

O Dockerfile executa as seguintes etapas:
1. Usa a imagem base `ubuntu:22.04`.
2. Define `DEBIAN_FRONTEND=noninteractive` para evitar prompts interativos durante a instalação de pacotes.
3. Instala o Apache2, MySQL Server, PHP e os módulos PHP necessários.
4. Copia o script `start.sh` e o torna executável.
5. Remove o arquivo `index.html` padrão do diretório raiz do Apache.
6. Copia o diretório `app/` para `/var/www/html/` (raiz web do Apache).
7. Copia o `init.sql` para `/root/` para inicialização do banco de dados.
8. Define as permissões corretas para o diretório raiz web.
9. Expõe a porta 80 para tráfego HTTP.
10. Usa o `start.sh` como ponto de entrada para iniciar os serviços.

## Como Construir e Executar

### Passo 1: Preparar os Arquivos Necessários

1. **Arquivos da Aplicação**: Coloque os arquivos da sua aplicação PHP no diretório `app/`.
2. **Inicialização SQL**: Crie um arquivo `init.sql` com os comandos SQL necessários para configurar seu banco de dados (por exemplo, criar bancos, tabelas ou inserir dados iniciais).
3. **Script de Inicialização**: Crie um script `start.sh` para inicializar o MySQL e iniciar o Apache. Abaixo está um exemplo de `start.sh`:

```bash
#!/bin/bash
service mysql start
mysql < /root/init.sql
service apache2 start
tail -f /var/log/apache2/access.log
```

Certifique-se de que o `start.sh` é executável no seu ambiente local:
```bash
chmod +x start.sh
```

### Passo 2: Construir a Imagem Docker

Execute o seguinte comando para construir a imagem Docker:

```bash
docker build -t minha-aplicacao-web .
```

- `-t minha-aplicacao-web`: Nomeia a imagem como `minha-aplicacao-web`.
- `.`: Especifica o diretório atual como o contexto de construção.

### Passo 3: Executar o Contêiner Docker com Volume

Para persistir os dados do MySQL, você deve usar um volume Docker ou um bind mount para o diretório de dados do MySQL (`/var/lib/mysql`). Isso garante que os dados do banco de dados não sejam perdidos quando o contêiner for parado ou removido.

Execute o contêiner com um volume:

```bash
docker run -d -p 8080:80 -v mysql-data:/var/lib/mysql --name meu-contêiner-web minha-aplicacao-web
```

- `-d`: Executa o contêiner em modo detached (em segundo plano).
- `-p 8080:80`: Mapeia a porta 8080 do host para a porta 80 do contêiner.
- `-v mysql-data:/var/lib/mysql`: Cria um volume nomeado `mysql-data` para persistir os dados do MySQL.
- `--name meu-contêiner-web`: Nomeia o contêiner como `meu-contêiner-web`.
- `minha-aplicacao-web`: O nome da imagem construída no Passo 2.

Alternativamente, você pode usar um bind mount para um diretório local em vez de um volume nomeado:

```bash
docker run -d -p 8080:80 -v /caminho/para/mysql/local:/var/lib/mysql --name meu-contêiner-web minha-aplicacao-web
```

Substitua `/caminho/para/mysql/local` pelo caminho absoluto para um diretório na sua máquina host.

### Passo 4: Acessar a Aplicação

Abra um navegador web e acesse:

```
http://localhost:8080
```

Isso exibirá sua aplicação PHP servida pelo Apache.

### Passo 5: Parar e Remover o Contêiner

Para parar o contêiner:

```bash
docker stop meu-contêiner-web
```

Para remover o contêiner (os dados no volume persistem):

```bash
docker rm meu-contêiner-web
```

Para remover o volume (se estiver usando um volume nomeado e quiser excluir os dados):

```bash
docker volume rm mysql-data
```

## Notas

- **Uso de Volume**: Os dados do MySQL são armazenados no diretório `/var/lib/mysql` dentro do contêiner. Usar um volume garante que os dados do banco de dados persistam entre reinicializações ou remoções do contêiner.
- **Script de Inicialização**: O script `start.sh` deve iniciar os serviços MySQL e Apache e manter o contêiner em execução (por exemplo, usando `tail -f` ou um comando semelhante).
- **Segurança**: Para uso em produção, proteja seu banco de dados MySQL configurando uma senha de root e restringindo o acesso. Atualize o `init.sql` para incluir essas configurações.
- **Personalização**: Modifique o conteúdo do diretório `app/` e o `init.sql` conforme necessário para sua aplicação.

## Solução de Problemas

- **Contêiner Para Imediatamente**: Verifique os logs com `docker logs meu-contêiner-web` para garantir que o `start.sh` está iniciando os serviços corretamente.
- **Problemas com MySQL**: Certifique-se de que o `init.sql` é válido e não causa erros durante a inicialização do banco de dados.
- **Conflitos de Porta**: Se a porta 8080 estiver em uso, escolha uma porta diferente no host (por exemplo, `-p 8081:80`).

Link dockerhub: https://hub.docker.com/repository/docker/lecndu/leandro/tags/latest/sha256-8e0251d66072fce455b007867f9feeef2c9ddcf0630175d591f65ec4ff4df8ec

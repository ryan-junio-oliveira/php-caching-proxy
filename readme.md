# PHP Caching Proxy Server

Este é um simples servidor proxy de cache construído em PHP. Ele encaminha as requisições para um servidor de origem e armazena as respostas em cache. Se a mesma solicitação for feita novamente dentro de um período definido (TTL), ele retorna a resposta do cache em vez de encaminhar a solicitação ao servidor.

### Funcionalidades

- Encaminha requisições para um servidor de origem.
- Armazena as respostas em cache localmente.
- TTL (Time To Live) configurável para o cache (atualmente definido para 5 minutos).
- Exibe cabeçalhos que indicam se a resposta veio do cache ou do servidor de origem:

    * X-Cache: HIT - quando a resposta é obtida do cache.
    * X-Cache: MISS - quando a resposta é obtida do servidor de origem.

### Requisitos

- PHP 7.4 ou superior
- Acesso ao terminal
- Extensão sockets do PHP habilitada
  
### Como Usar

- Clonar o Projeto

```bash
git clone https://github.com/seu-usuario/php-caching-proxy.git
cd php-caching-proxy
```

- Executar o Servidor Proxy

Para iniciar o servidor proxy, execute o seguinte comando no terminal:

```bash
php proxy.php --port <número_da_porta> --origin <url_do_servidor_de_origem>
```
Exemplo:

```bash
php proxy.php --port 3000 --origin http://dummyjson.com
```
Isso iniciará o servidor proxy em http://localhost:3000, e ele encaminhará as requisições para http://dummyjson.com.
Fazer uma Solicitação via Proxy

Depois de iniciar o servidor, você pode testar as requisições com curl ou no navegador. Por exemplo:

```bash
curl http://localhost:3000/products
```
A primeira vez que você fizer a solicitação, a resposta virá diretamente do servidor de origem e será armazenada em cache. As solicitações subsequentes retornarão a resposta armazenada em cache até que o TTL expire.

### Limpar o Cache

Para limpar manualmente o cache, basta remover os arquivos armazenados na pasta cache:

```bash
rm -rf cache/*
```

### Como Funciona

- Cache: As respostas são armazenadas localmente em arquivos na pasta cache/, com base em uma chave hashada (MD5) da URL solicitada.
- TTL: O cache expira após um tempo configurado (atualmente 5 minutos). Se o cache estiver expirado, uma nova solicitação é enviada ao servidor de origem, e o cache é atualizado.
- Verificação de Cache: O cabeçalho X-Cache é incluído nas respostas para indicar se a resposta veio do cache (HIT) ou do servidor de origem (MISS).

### Estrutura do Projeto

```bash
.
├── cache/               # Diretório onde os arquivos de cache são armazenados
├── Cache/               # Classe responsável por manipular o cache
├── Cache/               # Classe de serviço para manipular o cache
├── proxy.php            # Arquivo principal do servidor proxy
├── README.md            # Documentação do projeto

```

### Configurações

- Porta: Definida com o argumento --port ao iniciar o proxy.
- Servidor de origem: Definido com o argumento --origin ao iniciar o proxy.
- TTL (Time to Live): Definido no código (CACHE_TTL), atualmente configurado para 5 minutos (300 segundos).

### Erros Comuns

- Conexão falhou:
    Verifique se o servidor de origem está online e acessível.
- Cache vazio após solicitação:
    Verifique se a URL está correta e o servidor de origem responde corretamente.

### Melhorias Futuras

- Suporte a cabeçalhos ETag e Last-Modified para um cache mais eficiente.
- Implementação de opções para definir o TTL via CLI.
- Suporte a outros métodos HTTP além de GET (como POST e PUT).

### Contribuição

Sinta-se à vontade para enviar PRs ou abrir problemas com sugestões, correções ou melhorias!

### Licença

Este projeto é licenciado sob a licença MIT. Consulte o arquivo LICENSE para obter mais detalhes.

## Referências

Este projeto segue as diretrizes e estruturas propostas no [roadmap.sh](https://roadmap.sh/projects/caching-server).

- Link para o repositório do projeto: [Expense Tracker](https://github.com/ryan-junio-oliveira/php-caching-proxy).

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://www.tradeupgroup.com/wp-content/uploads/2021/12/tradeup-300x93-1.png" width="300" alt="TradeUp Group Logo"></a></p>

# Avaliação Técnica - TradeUp Group

Esse é um teste técnico para a vaga de **Backend** para a empresa **TradeUp Group**.

## Requisitos

Criar uma aplicação em **Laravel** com **Vuex**, que realiza uma consulta de **CEP** a partir de uma API externa.

### Frontend

É necessário ter pelo menos duas páginas:
- HOME - Página principal do mini sistema, com uma descrição, imagem principal e botão para a página **CONSULTA CEP**
- CONSULTA CEP - Página com um campo para pesquisa do CEP (que aceite somente números, somente no formato de um cep), que retorna o endereço do cep pesquisado, amigavelmente, um botão voltar, que volta para a página de **HOME**.

### Backend

Será realizado utilizando o framework laravel, e será o responsável por consumir a API externa para a consulta de cep.


## Funcionalidades

- Consultar CEP
- Preview em tempo real
- Tela com um bom layout


## Variáveis de Ambiente

Para rodar esse projeto, você vai precisar adicionar as seguintes variáveis de ambiente no seu .env

`API_KEY`

`ANOTHER_API_KEY`


## Instalação

A partir de um ambiente de desenvolvimento composto pelo PHP na versão 8.2 ou superior e com o Composer instalado execute o comando dentro da pasta do projeto.

```bash
  composer install
```
    
## Rodando os testes

Para rodar os testes, rode o seguinte comando

```bash
  npm run test
```


## Documentação

- [PHP](https://www.php.net/manual/pt_BR/index.php)
- [Laravel](https://laravel.com/docs)
- [Composer](https://getcomposer.org/)
- [Vuex](https://vuex.vuejs.org/)
- [Vuejs](https://vuejs.org/)


## Documentação da API

A documentação da API é gerada através do [Swagger](https://swagger.io/) pela biblioteca `darkaonline/l5-swagger`. 

#### Retorna todos os itens

```http
  GET /api/items
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `api_key` | `string` | **Obrigatório**. A chave da sua API |

#### Retorna um item

```http
  GET /api/items/${id}
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` | **Obrigatório**. O ID do item que você quer |

#### add(num1, num2)

Recebe dois números e retorna a sua soma.


## Stack utilizada

**Front-end:** Vuejs, Vuex, Typescript

**Back-end:** Laravel, PHP


## Autores

- [Github - @danilo-righetto](https://github.com/danilo-righetto)
- [Linkedin - @danilo-righetto](https://www.linkedin.com/in/danilo-righetto/)

## Licença

Esta aplicação é um software de código aberto licenciado pelo [MIT license](https://opensource.org/licenses/MIT).


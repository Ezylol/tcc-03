# PixelSwap

PixelSwap é um marketplace fictício de jogos independentes e troca de chaves digitais, desenvolvido como projeto acadêmico para demonstrar integração entre frontend, backend, banco de dados e APIs REST.

## 1. Objetivo do projeto

O objetivo deste projeto é simular um ecossistema real de compra, venda e negociação de keys digitais de jogos indie. O sistema foi pensado para uso didático, mostrando como um ambiente web pode conectar:

- interface HTML/CSS/JavaScript
- API REST em PHP
- comunicação com MySQL via PDO
- autenticação por sessão
- validação e proteção de rotas
- transações para operações sensíveis

## 2. Tecnologias utilizadas

- PHP 8+
- MySQL / MariaDB
- PDO
- HTML5 semântico
- CSS3 moderno
- JavaScript ES6+
- Fetch API
- XAMPP
- Postman
- Git/GitHub

## 3. Estrutura de arquivos

```text
pixelswap/
├── .htaccess
├── .gitignore
├── index.php
├── marketplace.php
├── game.php
├── login.php
├── register.php
├── profile.php
├── seller.php
├── api/
│   ├── index.php
│   ├── auth/
│   │   ├── register.php
│   │   ├── login.php
│   │   ├── logout.php
│   │   └── me.php
│   ├── games/
│   │   ├── list.php
│   │   └── show.php
│   ├── listings/
│   │   ├── list.php
│   │   ├── create.php
│   │   ├── update.php
│   │   └── delete.php
│   ├── orders/
│   │   ├── list.php
│   │   └── create.php
│   ├── trades/
│   │   ├── list.php
│   │   ├── create.php
│   │   ├── accept.php
│   │   └── reject.php
│   └── wishlist/
│       ├── list.php
│       ├── create.php
│       └── delete.php
├── config/
│   └── database.php
├── includes/
│   └── functions.php
├── frontend/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── app.js
│   └── assets/
├── database/
│   ├── database.sql
│   └── seed.sql
├── postman/
│   └── PixelSwap.postman_collection.json
├── README.md
└── .gitignore
```

## 4. Modelo e relacionamentos do banco

O banco principal é chamado `pixelswap` e contém as tabelas principais abaixo:

- `users`: usuário, senha e perfil.
- `games`: dados do catálogo de jogos indies.
- `game_keys`: chaves digitais fictícias e seus proprietários.
- `listings`: anúncios de venda ou troca.
- `orders`: compras simuladas.
- `trades`: propostas de troca.
- `wishlists`: lista de desejos por usuário.

Relacionamentos:

- `users` tem relação 1:N com `listings`, `orders`, `game_keys` e `wishlists`.
- `games` tem relação 1:N com `game_keys`, `listings` e `wishlists`.
- `game_keys` é vinculada ao jogo e ao dono atual.
- `listings` aponta para o vendedor e para o jogo.
- `orders` registra a compra de um anúncio por um comprador.
- `trades` conecta dois usuários, uma key oferecida e um anúncio solicitado.

Essas relações garantem organização e integridade dos dados, além de facilitar consultas com JOIN e transações de compra/troca.

## 5. Instalação com XAMPP

1. Instale o XAMPP e inicie os serviços de Apache e MySQL.
2. Acesse o phpMyAdmin em http://localhost/phpmyadmin.
3. Crie o banco `pixelswap`.
4. Importe o arquivo `database/database.sql`.
5. Se desejar dados de demonstração, importe também `database/seed.sql`.
6. Coloque a pasta do projeto dentro de `htdocs` do XAMPP.
7. Acesse o projeto em http://localhost/pixelswap.

## 6. Como importar o banco pelo phpMyAdmin

1. Abra http://localhost/phpmyadmin.
2. Clique em "Novo".
3. Crie o banco de dados chamado `pixelswap`.
4. Selecione o banco e escolha a opção "Importar".
5. Carregue o arquivo `database/database.sql`.
6. Faça o mesmo para `database/seed.sql` se quiser populá-lo.

## 7. Como executar o site localmente

Depois que a pasta for movida para o diretório `htdocs`:

- http://localhost/pixelswap/
- http://localhost/pixelswap/marketplace.php
- http://localhost/pixelswap/login.php

O projeto espera que o Apache interprete arquivos PHP e o MySQL esteja disponível.

## 8. API REST e rotas principais

Os endpoints são centralizados em `api/index.php` e recebem a rota via query string `?route=` ou pelo rewrite do `.htaccess`.

Exemplos:

- GET /api/index.php?route=games
- GET /api/index.php?route=games/1
- POST /api/index.php?route=auth/register
- POST /api/index.php?route=auth/login
- GET /api/index.php?route=auth/me
- GET /api/index.php?route=listings
- POST /api/index.php?route=listings
- POST /api/index.php?route=orders
- POST /api/index.php?route=trades
- PATCH /api/index.php?route=trades/1/accept

Em um ambiente com Apache e mod_rewrite ativado, as rotas podem ser acessadas no formato mais amigável:

- /api/games
- /api/listings
- /api/trades

## 9. Como usar a coleção Postman

1. Abra o Postman.
2. Importe o arquivo `postman/PixelSwap.postman_collection.json`.
3. Configure a variável `base_url` para `http://localhost/pixelswap`.
4. Execute as requisições na ordem recomendada:
   - registro
   - login
   - listagem de jogos
   - criação de anúncio
   - compra simulada
   - proposta de troca
   - aceite/rejeição da troca

## 10. Exemplos de uso do sistema

- Usuário cria conta e faz login.
- Busca jogos por nome, gênero ou preço.
- Cria um anúncio para vender ou trocar uma key.
- Compra uma oferta disponível pela API.
- Envia proposta de troca para outro usuário.
- O destinatário aceita ou recusa a negociação.
- A biblioteca e a lista de desejos são atualizadas conforme o fluxo.

## 11. Limitações conhecidas

- Este projeto é didático e usa dados fictícios.
- Não há integração real com gateways de pagamento.
- As keys digitais são demonstrativas e não podem ser ativadas.
- O sistema não foi projetado para ambiente de produção sem ajustes extras.

## 12. Melhorias futuras

- usar criptografia para armazenamento de keys reais
- integrar com Stripe, PayPal ou gateway local seguro
- implementar CSRF token em formulários e AJAX
- adicionar paginação real em marketplace
- melhorar painel do vendedor com múltiplos anúncios por jogo
- incluir dashboard para administradores
- adicionar testes automatizados com PHPUnit
- criar uma camada de cache e validação mais detalhada

## 13. Observações finais

Este projeto foi construído para demonstrar um fluxo realista de desenvolvimento Full Stack com foco em arquitetura, organização e clareza didática. Ele simula um ambiente funcional de compra, venda e negociação de gaming keys em um contexto acadêmico.

Sempre que for implantar em produção, serão necessários reforços em segurança, criptografia, autenticação e validação em múltiplas camadas.

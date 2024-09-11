1. Faça a clone do projeto.
2. Instale as dependências utilizando o comando "composer install", lembrando que é necessário ter instalado o PHP 8.2, mysql 8.0 e o composer no seu computador.
3. Certifique de configurar o ENV de acordo com sua necessidade.
4. Executa o comando "php artisan migrate" para criar as tabelas, caso ja tenha executado e precise resetar utilize o comando "php artisan migrate:refresh";
5. Executa o comando "php artisan db:seed" para colocar algumas informações já na sua tabela de produtos.
6. Execute o comando "php artisan serve" para dar inicio a sua aplicação.
7. Você poderá utilizar o postman juntamente com o arquivo anexado(Products.postman_collection.json na raiz do projeto) para testar as rotas, no postman tem uma varial global "URL_LOCAL" com o caminho do path(ele é exibido ao utilizar o comando "php artisan serve" na sua maquina local).
8. Para executar os testes unitários utilize o comando "php artisan test".

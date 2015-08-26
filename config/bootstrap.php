 <?php
     return [
             'pdo' => new PDO(
                            'mysql:host=127.0.0.1;dbname=dbname',
                            'dbuser',
                            'dbpassword'
                          )
            ];

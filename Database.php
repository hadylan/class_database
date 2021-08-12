<?php

/**
 * Todo : PhpDoc
 */

declare(strict_types=1);

/**
 * Database class is a simple class for connection and execution of SQL query on MySQL databases
 * 
 * @author Dylan.H <dylan.han82@gmail.com> on a base of David.T <d.trouiller@aformac.fr>
 * @copyright Copyright (c) 2020 Database Project, contact me for copying permissions
 * @version 1.0.0

 * Exemple usage:
 * require_once(class/Database.php);
 * $db = new Database();
 * $db->query('INSERT INTO `users` (`username`,`email`,`password`) VALUES (?, ?, ?)', array("X", "Y", "Z"));
 */

class Database
{

    // Constant

    private const CONFIGURATION_FILE = __DIR__ . './configuration.php';



    // Attributes

    /**
     * Required argument for PDO instance.
     * @var $host (string) : Containt hostname of database
     */
    private static string $host;

    /**
     * Required argument for PDO instance.
     * @var $dbName (string) : Containt name of database
     */
    private static string $dbName;

    /**
     * Required argument for PDO instance.
     * @var $username (string) : Containt username of database
     */
    private static string $username;

    /**
     * Required argument for PDO instance.
     * @var $password (string) : Containt password of database
     */
    private static string $password;

    /**
     * PDO Object instanciation.
     * @var $database (object) : Contain PDO object
     */
    private static $database;



    // Methods

    /** 
     * checkConfiguration() is a private method called in query() method for check the configuration file and his content.
     * @return void
     */
    private static function checkConfiguration(): void
    {
        (file_exists(self::CONFIGURATION_FILE)) ? require_once(self::CONFIGURATION_FILE) : die('Configuration file does not exist');
        (isset($database['username']) and !empty($database['username']) ? self::setUsername($database['username']) : die("Configuration file have not 'username' key"));
        (isset($database['password']) and !empty($database['password']) ? self::setpassword($database['password']) : die("Configuration file have not 'password' key"));
        (isset($database['dbname']) and !empty($database['dbname']) ? self::setdbname($database['dbname']) : die("Configuration file have not 'dbname' key"));
        (isset($database['host']) and !empty($database['host']) ? self::sethost($database['host']) : die("Configuration file have not 'host' key"));
    }

    /**
     * connection() is a private method called in query() method for establishing a connection to the database.
     * @return void
     */
    private static function connection(): void
    {
        try {
            $db = (object) new PDO('mysql:host=' . self::getHost() . ';dbname=' . self::getDbName(), self::getUsername(), self::getPassword());
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        } finally {
            self::$database = (object) $db;
        }
    }

    /**
     * query() is a private method for send a query to the database.
     * @param $query (string), $params (array)
     * @return array
     */
    public static function query(string $query, array $params = array()): string
    {
        self::checkConfiguration();
        self::connection();
        $sth = self::$database->prepare($query);
        $sth->execute($params);
        return ($sth->rowCount() > 0 ? self::fetchQuery($sth) : die());
    }

    /**
     * fetchQuery() is a private method called in query() method for return results of a query.
     * @param $stmt (object)
     * @return array
     */
    private static function fetchQuery(object $stmt): string {
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }



    // Accessors SETTER

    /**
     * setHost(argument) is a private setter accessor for define value of $host attribut.
     * @param $host (string)
     * @return void
     */
    private static function setHost(string $host): void
    {
        self::$host = (string) $host;
    }

    /**
     * setDbName(argument) is a private setter accessor for define value of $dbName attribut.
     * @param $dbname (string)
     * @return void
     */
    private static function setDbName(string $dbName): void
    {
        self::$dbName = (string) $dbName;
    }

    /**
     * setUsername(argument) is a private setter accessor for define value of $username attribut.
     * @param $username (string)
     * @return void
     */
    private static function setUsername(string $username): void
    {
        self::$username = (string) $username;
    }

    /**
     * setPassword(argument) is a private setter accessor for define value of $password attribut.
     * @param $password (string)
     * @return void
     */
    private static function setPassword(string $password): void
    {
        self::$password = (string) $password;
    }



    // Accessors GETTER

    /**
     * getHost() is a private getter accessor for get value of $host attribut.
     * @return $host (string)
     */
    private static function getHost(): string
    {
        return (string) self::$host;
    }

    /**
     * getDbName() is a private getter accessor for get value of $dbName attribut.
     * @return $dbName (string)
     */
    private static function getDbName(): string
    {
        return (string) self::$dbName;
    }

    /**
     * getUsername() is a private getter accessor for get value of $username attribut.
     * @return $username (string)
     */
    private static function getUsername(): string
    {
        return (string) self::$username;
    }

    /**
     * getPassword() is a private getter accessor for get value of $password attribut.
     * @return $password (string)
     */
    private static function getPassword(): string
    {
        return (string) self::$password;
    }
}
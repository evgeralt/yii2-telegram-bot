<?php

namespace evgeralt\yii2telegram;

use Longman\TelegramBot\Telegram;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\di\Instance;

/**
 * @property-read Telegram $client
 */
class TelegramBot extends Component
{
    public $api_key;
    public $bot_name;
    public $comandsPaths;
    public $withoutDb = false;
    /** @var Connection */
    public $dbConection = 'db';
    /** @var Telegram */
    protected $_client;

    /**
     * @inheritDoc
     */
    public function init()
    {
        if (!$this->api_key) {
            throw new InvalidConfigException("api_key is required");
        }
        if (!$this->bot_name) {
            throw new InvalidConfigException("bot_name is required");
        }
        $this->_client = new Telegram($this->api_key, $this->bot_name);

        $this->dbConection = Instance::ensure($this->dbConection, Connection::class);
        if (!$this->withoutDb) {
            $this->initMysql();
        }
        $this->_client->addCommandsPaths($this->comandsPaths);
    }

    public function getClient(): Telegram
    {
        return $this->_client;
    }

    protected function initMysql(): void
    {
        if ($this->dbConection->getDriverName() !== 'mysql') {
            throw new InvalidConfigException("only mysql connection");
        }
        $dsn = $this->parseDsn();
        $this->_client->enableMySql([
            'host' => $dsn['host'],
            'port' => $dsn['port'], // optional
            'user' => $this->dbConection->username,
            'password' => $this->dbConection->password,
            'database' => $dsn['dbname'],
        ]);
    }

    protected function parseDsn(): array
    {
        // todo !
        $str = $this->dbConection->dsn;
        $str = explode('mysql:', $str)[1];
        $res = [];
        foreach (explode(';', $str) as $item) {
            $item = explode('=', $item);
            $res[$item[0]] = $item[1];
        }

        return $res;
    }
}

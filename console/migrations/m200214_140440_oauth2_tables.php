<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200214_140440_oauth2_tables
 */
class m200214_140440_oauth2_tables extends Migration
{
    private $_tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (static::_tables() as $name => $attributes) {
            try {
                $this->createTable($name, $attributes, $this->_tableOptions);
            } catch (Exception $e) {
                echo $e->getMessage(), "\n";
                return false;
            }
        }

        return true;
    }

    private function _tables()
    {

        return [
            '{{%auth_client}}' => [
                'id' => Schema::TYPE_PK,
                'identifier' => Schema::TYPE_STRING . ' NOT NULL',
                'secret' => Schema::TYPE_STRING, // not confidential if null
                'name' => Schema::TYPE_STRING . ' NOT NULL',
                'redirect_uri' => Schema::TYPE_STRING,
                'token_type' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1', // Bearer
                'grant_type' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1', // Authorization Code
                'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1', // Active,
                'KEY (token_type)',
                'KEY (grant_type)',
                'KEY (status)',
            ],
            '{{%auth_access_token}}' => [
                'id' => Schema::TYPE_PK,
                'client_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'user_id' => Schema::TYPE_INTEGER,
                'identifier' => Schema::TYPE_STRING . ' NOT NULL',
                'type' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1', // Bearer
                'mac_key' => Schema::TYPE_STRING,
                'mac_algorithm' => Schema::TYPE_SMALLINT,
                'allowance' => Schema::TYPE_SMALLINT,
                'allowance_updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'expired_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1', // Active,
                'FOREIGN KEY (client_id) REFERENCES {{%auth_client}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
                'KEY (type)',
                'KEY (mac_algorithm)',
                'KEY (status)',
            ],
            '{{%auth_scope}}' => [
                'id' => Schema::TYPE_PK,
                'identifier' => Schema::TYPE_STRING . ' NOT NULL',
                'name' => Schema::TYPE_STRING,
            ],
            '{{%auth_client_scope}}' => [
                'id' => Schema::TYPE_PK,
                'client_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'scope_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL', // common if null
                'grant_type' => Schema::TYPE_SMALLINT . ' DEFAULT NULL', // all grants if null
                'is_default' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
                'UNIQUE KEY (client_id, scope_id, user_id, grant_type)',
                'FOREIGN KEY (client_id) REFERENCES {{%auth_client}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
                'FOREIGN KEY (scope_id) REFERENCES {{%auth_scope}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
                'KEY (grant_type)',
                'KEY (is_default)',
            ],
            '{{%auth_access_token_scope}}' => [
                'access_token_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'scope_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'PRIMARY KEY (access_token_id, scope_id)',
                'FOREIGN KEY (access_token_id) REFERENCES {{%auth_access_token}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
                'FOREIGN KEY (scope_id) REFERENCES {{%auth_scope}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            '{{%auth_refresh_token}}' => [
                'id' => Schema::TYPE_PK,
                'access_token_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'identifier' => Schema::TYPE_STRING . ' NOT NULL',
                'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'expired_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
                'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1', // Active,
                'FOREIGN KEY (access_token_id) REFERENCES {{%auth_access_token}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
                'KEY (status)',
            ],
            '{{%auth_auth_code}}' => [
                'id' => Schema::TYPE_PK,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach (array_reverse(static::_tables()) as $name => $attributes) {
            try {
                $this->dropTable($name);
            } catch (Exception $e) {
                echo "m160920_072449_oauth cannot be reverted.\n";
                echo $e->getMessage(), "\n";
                return false;
            }
        }

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200214_140440_oauth2_tables cannot be reverted.\n";

        return false;
    }
    */
}

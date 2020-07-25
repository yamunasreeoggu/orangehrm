<?php

/**
 * BaseOAuthClient
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string       $clientId                    Type: string(80), primary key
 * @property string       $clientSecret                Type: string(80)
 * @property string       $redirectUri                 Type: string(2000)
 * @property string       $scope                       Type: string(4000)
 * @property string       $grantTypes                  Type: string(80)
 *  
 * @method string         getClientid()                Type: string(80), primary key
 * @method string         getClientsecret()            Type: string(80)
 * @method string         getRedirecturi()             Type: string(2000)
 * @method string         getScope()                   Type: string(4000)
 * @method string         getGrantTypes()              Type: string(80)
 *  
 * @method OAuthClient    setClientid(string $val)     Type: string(80), primary key
 * @method OAuthClient    setClientsecret(string $val) Type: string(80)
 * @method OAuthClient    setRedirecturi(string $val)  Type: string(2000)
 * @method OAuthClient    setScope(string $val)        Type: string(4000)
 * @method OAuthClient    setGrantTypes(string $val)   Type: string(80)
 *  
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseOAuthClient extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_oauth_client');
        $this->hasColumn('client_id as clientId', 'string', 80, array(
             'type' => 'string',
             'primary' => true,
             'length' => 80,
             ));
        $this->hasColumn('client_secret as clientSecret', 'string', 80, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 80,
             ));
        $this->hasColumn('redirect_uri as redirectUri', 'string', 2000, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 2000,
             ));
        $this->hasColumn('scope', 'string', 4000, array(
             'type' => 'string',
             'length' => 4000,
             ));
        $this->hasColumn('grant_types as grantTypes', 'string', 80, array(
             'type' => 'string',
             'length' => 80,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}

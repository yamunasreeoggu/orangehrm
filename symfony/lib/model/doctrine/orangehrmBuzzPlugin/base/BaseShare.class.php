<?php

/**
 * BaseShare
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int                                  $id                                  Type: integer, primary key
 * @property int                                  $post_id                             Type: integer
 * @property int                                  $employee_number                     Type: integer(7)
 * @property string                               $employee_name                       Type: string
 * @property int                                  $number_of_likes                     Type: integer(6)
 * @property int                                  $number_of_unlikes                   Type: integer(6)
 * @property int                                  $number_of_comments                  Type: integer(6)
 * @property string                               $share_time                          Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @property int                                  $type                                Type: int(1)
 * @property string                               $text                                Type: string(600)
 * @property string                               $updated_at                          Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @property Employee                             $employeePostShared                  
 * @property Post                                 $PostShared                          
 * @property Doctrine_Collection|Comment[]        $comment                             
 * @property Doctrine_Collection|LikeOnShare[]    $Like                                
 * @property Doctrine_Collection|UnLikeOnShare[]  $Unlike                              
 *  
 * @method int                                    getId()                              Type: integer, primary key
 * @method int                                    getPostId()                          Type: integer
 * @method int                                    getEmployeeNumber()                  Type: integer(7)
 * @method string                                 getEmployeeName()                    Type: string
 * @method int                                    getNumberOfLikes()                   Type: integer(6)
 * @method int                                    getNumberOfUnlikes()                 Type: integer(6)
 * @method int                                    getNumberOfComments()                Type: integer(6)
 * @method string                                 getShareTime()                       Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method int                                    getType()                            Type: int(1)
 * @method string                                 getText()                            Type: string(600)
 * @method string                                 getUpdatedAt()                       Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method Employee                               getEmployeePostShared()              
 * @method Post                                   getPostShared()                      
 * @method Doctrine_Collection|Comment[]          getComment()                         
 * @method Doctrine_Collection|LikeOnShare[]      getLike()                            
 * @method Doctrine_Collection|UnLikeOnShare[]    getUnlike()                          
 *  
 * @method Share                                  setId(int $val)                      Type: integer, primary key
 * @method Share                                  setPostId(int $val)                  Type: integer
 * @method Share                                  setEmployeeNumber(int $val)          Type: integer(7)
 * @method Share                                  setEmployeeName(string $val)         Type: string
 * @method Share                                  setNumberOfLikes(int $val)           Type: integer(6)
 * @method Share                                  setNumberOfUnlikes(int $val)         Type: integer(6)
 * @method Share                                  setNumberOfComments(int $val)        Type: integer(6)
 * @method Share                                  setShareTime(string $val)            Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method Share                                  setType(int $val)                    Type: int(1)
 * @method Share                                  setText(string $val)                 Type: string(600)
 * @method Share                                  setUpdatedAt(string $val)            Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method Share                                  setEmployeePostShared(Employee $val) 
 * @method Share                                  setPostShared(Post $val)             
 * @method Share                                  setComment(Doctrine_Collection $val) 
 * @method Share                                  setLike(Doctrine_Collection $val)    
 * @method Share                                  setUnlike(Doctrine_Collection $val)  
 *  
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseShare extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_buzz_share');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '',
             ));
        $this->hasColumn('post_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '',
             ));
        $this->hasColumn('employee_number', 'integer', 7, array(
             'type' => 'integer',
             'length' => 7,
             ));
        $this->hasColumn('employee_name', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('number_of_likes', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
        $this->hasColumn('number_of_unlikes', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
        $this->hasColumn('number_of_comments', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
        $this->hasColumn('share_time', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('type', 'int', 1, array(
             'type' => 'int',
             'length' => 1,
             ));
        $this->hasColumn('text', 'string', 600, array(
             'type' => 'string',
             'length' => 600,
             ));
        $this->hasColumn('updated_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Employee as employeePostShared', array(
             'local' => 'employee_number',
             'foreign' => 'empNumber'));

        $this->hasOne('Post as PostShared', array(
             'local' => 'post_id',
             'foreign' => 'id'));

        $this->hasMany('Comment as comment', array(
             'local' => 'id',
             'foreign' => 'share_id'));

        $this->hasMany('LikeOnShare as Like', array(
             'local' => 'id',
             'foreign' => 'share_id'));

        $this->hasMany('UnLikeOnShare as Unlike', array(
             'local' => 'id',
             'foreign' => 'share_id'));
    }
}
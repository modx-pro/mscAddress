<?php
$xpdo_meta_map['msCustomerAddress'] = [
  'package' => 'mscaddress',
  'version' => '1.1',
  'table' => 'ms2_customer_addresses',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  [
    'user_id' => NULL,
    'rank' => 0,
    'title' => NULL,
    'receiver' => NULL,
    'phone' => NULL,
    'email' => NULL,
    'country' => NULL,
    'index' => NULL,
    'region' => NULL,
    'city' => NULL,
    'metro' => NULL,
    'street' => NULL,
    'building' => NULL,
    'entrance' => NULL,
    'floor' => NULL,
    'room' => NULL,
    'comment' => NULL,
    'text_address' => NULL,
    'properties' => NULL,
  ],
  'fieldMeta' => 
  [
    'user_id' => 
    [
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ],
    'rank' => 
    [
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ],
    'title' => 
    [
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ],
    'receiver' => 
    [
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ],
    'phone' => 
    [
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => true,
    ],
    'email' =>
    [
        'dbtype' => 'varchar',
        'precision' => '191',
        'phptype' => 'string',
        'null' => true,
    ],
    'country' => 
    [
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ],
    'index' => 
    [
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => true,
    ],
    'region' => 
    [
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ],
    'city' => 
    [
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ],
    'metro' => 
    [
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ],
    'street' => 
    [
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ],
    'building' => 
    [
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
    ],
    'room' => 
    [
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
    ],
    'comment' => 
    [
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ],
    'properties' => 
    [
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
    ],
  ],
  'indexes' => 
  [
    'user_id' => 
    [
      'alias' => 'user_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      [
        'user_id' => 
        [
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ],
      ],
    ],
  ],
  'aggregates' => 
  [
    'User' => 
    [
      'class' => 'modUser',
      'local' => 'user_id',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ],
    'UserProfile' => 
    [
      'class' => 'modUserProfile',
      'local' => 'user_id',
      'foreign' => 'internalKey',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ],
    'CustomerProfile' => 
    [
      'class' => 'msCustomerProfile',
      'local' => 'user_id',
      'foreign' => 'internalKey',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ],
  ],
];

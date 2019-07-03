<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_videos extends CI_Migration {

        public function up()
        {
            $this->dbforge->add_field(array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 5,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'description' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '255',
                ),
                'url' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '255',
                ),
                'thumbnail' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                ),
                'lang' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '5',
                ),
                'upload_date' => array(
                    'type' => 'DATETIME',
                    'constraint' => '6',
                ),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('videos');
        }

        public function down()
        {
            $this->dbforge->drop_table('videos');
        }
    }
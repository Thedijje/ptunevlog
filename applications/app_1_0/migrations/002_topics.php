<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_topics extends CI_Migration {

        public function up()
        {
            $this->dbforge->add_field(array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => '5',
                ),
                'name' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                ),
            ));
            
            $this->dbforge->create_table('topics');
        }

        public function down()
        {
            $this->dbforge->drop_table('topics');
        }
    }
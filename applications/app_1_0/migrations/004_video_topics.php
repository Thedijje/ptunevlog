<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Video_topics extends CI_Migration {

        public function up()
        {
            $this->dbforge->add_field(array(
                'video_id' => array(
                        'type' => 'INT',
                        'constraint' => '5',
                ),
                'topic_id' => array(
                        'type' => 'INT',
                        'constraint' => '5',
                ),
            ));
            
            $this->dbforge->create_table('video_topics');
        }

        public function down()
        {
            $this->dbforge->drop_table('video_topics');
        }
    }
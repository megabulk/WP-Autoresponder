<?php
/*
Given below is the database structure of the plugin. The installation procedure 
and the table integrity checker uses the following rules to make sure that the 
table is of the correct type.
*/

$database_structure = array();

$database_structure["wpr_subscribers"] = array ('columns' => array(
                                                                    'nid'=> "INT NOT NULL",
                                                                    'id'=> "INT NOT NULL",
                                                                    'name'=> "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                    'email'=> "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                    'date'=> "VARCHAR(12) NOT NULL",
                                                                    'active'=> "TINYINT(1) NOT NULL DEFAULT '0'",
                                                                    'confirmed'=> "TINYINT(1) NOT NULL DEFAULT '0'",
                                                                    'fid'=> "TINYINT(1) NOT NULL DEFAULT '1'",
                                                                    'hash'=> "VARCHAR(50) NOT NULL"
                                                            ),
                                                'primary_key'=>array('id'),
                                                'auto_increment'=>'id',
                                                'unique' => array(                                                                     
                                                                     "unique_email_for_newsletter"    => array("nid","email")
                                                                 )
                                                );


$database_structure["wpr_subscriber_transfer"] = array('columns' => array(
                                                                          'id'=>"TINYINT unsigned NOT NULL",
                                                                          'source'=>'TINYINT unsigned NOT NULL',
                                                                          'dest'=>'TINYINT unsigned NOT NULL',
                                                                          ),

                                        'primary_key' => "id",
                                        'auto_increment' => 'id',
                                        'unique' => array(
                                                            "unique_rules"=> array ("source","dest")
                                                        )
                                        );
$database_structure["wpr_subscription_form"] = array (
													  
                                                      'columns' => array(
                                                                          'id' => "INT NOT NULL",
                                                                          'name' => "VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'return_url' => "VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'followup_type' => "enum('postseries','autoresponder','none') NOT NULL",
                                                                          'followup_id' => "INT NOT NULL",
                                                                          'blogsubscription_type' => "enum('all','cat','none') NOT NULL",
                                                                          'blogsubscription_id' => "INT NOT NULL",
                                                                          'nid' => "INT NOT NULL",
                                                                          'custom_fields' => "VARCHAR(100) NOT NULL",
                                                                          'confirm_subject' => "TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirm_body' => "TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirmed_subject' => "TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirmed_body' => "TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirm_url' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'submit_button' => "VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'Subscribe'"
                                                                  ),

                                                            'primary_key'=> "id",
                                                            'auto_increment' => 'id',
                                                            'unique'=> array(
                                                                                                    "unique_subscription_form_names"=>array('name')
                                                                                             )
                                                    );

$database_structure["wpr_queue"] = array (
                                            'columns'=>  array(
                                                              'id' => "INT NOT NULL",
                                                              'from' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                              'fromname' => "VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                              'to' => "VARCHAR(100) NOT NULL",
															  'reply_to'=> 'VARCHAR(100)  CHARACTER SET utf8 COLLATE utf8_bin NOT NULL',
                                                              'subject' => "text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                              'htmlbody' => "text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                              'textbody' => "text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                              'headers' => "text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                              'sent' => "INT NOT NULL",
															  'date' => 'INT NOT NULL DEFAULT UNIX_TIMESTAMP()',
															  'delivery_type' =>"tinyint(1) NOT NULL DEFAULT '0'",
															  'email_type' => "enum('user_verify_email','user_confirmed_email','user_followup_autoresponder_email','user_followup_postseries_email','user_blogsubscription_email','user_blogcategorysubscription_email','user_unsubscribed_notification_email','critical_queue_limit_approaching_email','system_subscription_errors_email','system_analytics_email','misc') COLLATE utf8_bin NOT NULL DEFAULT 'misc'",
															  'hash'=> 'VARCHAR(32)  NOT NULL',
															  'meta_key'=> 'VARCHAR(30)  NOT NULL',
                                                              'htmlenabled' => "TINYINT NOT NULL",
                                                              'attachimages' => "TINYINT NOT NULL"
                                                     ),
                                            'auto_increment'=> 'id',
                                            'primary_key'=> "id",
                                            'unique'=>array(
															"hash_is_unique" => array('hash'),
															"meta_key_is_unique" => array('meta_key')
														)
                              );


/*	$queries[] = "ALTER TABLE `".$prefix."wpr_queue` ADD `sid` INT NOT NULL;";
	$queries[] = "ALTER TABLE `".$prefix."wpr_queue` ADD `hash` VARCHAR(32)  NOT NULL";
	$queries[] = "ALTER TABLE `".$prefix."wpr_queue` ADD UNIQUE KEY `hash_1` (`hash`);";
	$queries[] = "ALTER TABLE `".$prefix."wpr_queue` ADD `meta_key` VARCHAR(30) NOT NULL";
	$queries[] = "ALTER TABLE `".$prefix."wpr_queue` ADD UNIQUE KEY `meta_key_1` (`meta_key`);";
	
*/

$database_structure["wpr_newsletter_mailouts"] = array ( 'columns'=> array(
                                                                              'id' => "INT NOT NULL",
                                                                              'nid' => "INT NOT NULL",
                                                                              'subject' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                                              'textbody' => "TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                              'htmlbody' => "TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                              'time' => "VARCHAR(25) NOT NULL",
                                                                              'status' => "TINYINT NOT NULL",
                                                                              'recipients' => "TEXT NOT NULL",
                                                                              'attachimages' => "TINYINT NOT NULL",
                                                                       ),
                                                     'auto_increment'=>'id',
                                                     'primary_key'=> "id",
                                                     "unique" => array()
                                                    );


$database_structure["wpr_newsletters"] = array ( 'columns'=> array(
                                                                    'id' => "INT NOT NULL",
                                                                          'name' => "VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'reply_to' => "VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'description' => "text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirm_subject' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirm_body' => "text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirmed_subject' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'confirmed_body' => "text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'fromname' => "VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                          'fromemail' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL"
                                                                        ),
                                                  'primary_key' => "id",
                                                  'auto_increment'=>'id',
                                                  "unique" => array(
                                                                    "unique_name_for_newsletters" => array("name")
                                                            )
                                                  );

$database_structure["wpr_followup_subscriptions"] = array ( 'columns'=> array(
																   
                                                                    'id' => "INT NOT NULL ",
                                                                          'sid' => "INT NOT NULL",
                                                                          'type' => "enum('autoresponder','postseries') NOT NULL",
                                                                          'eid' => "INT NOT NULL",
                                                                          'sequence' => "SMALLINT NOT NULL",
                                                                          'last_date' => "INT NOT NULL",
                                                                          'doc' => "VARCHAR(20) NOT NULL"
                                                                          ),
                                                    'primary_key' => "id",
                                                    'auto_increment' => 'id',
                                                    'unique' => array(
                                                                      "unique_subscriptions_for_subscribers" => array("sid","type","eid")
                                                              )
                                                    );



$database_structure["wpr_custom_fields_values"] = array ( 'columns'=> array(
                                                                              'id' => "INT NOT NULL AUTO_INCREMENT",
                                                                              'nid' => "INT NOT NULL",
                                                                              'sid' => "INT NOT NULL",
                                                                              'cid' => "INT NOT NULL",
                                                                              'value' => "text  CHARACTER SET utf8 COLLATE utf8_bin NOT NULL"
                                                                            ),
                                     'primary_key' => "id",
                                     'auto_increment'=>'id',
                                     'unique' => array(
                                                        "only_one_per_subscriber_per_field" => array( "nid","cid","sid")
                                           )
                                     );


$database_structure["wpr_custom_fields"] = array ( 'columns'=> array(
																	 
                                                                      'id' => "INT NOT NULL",
                                                                      'nid' => "INT NOT NULL",
                                                                      'type' => "enum('enum','text','hidden') NOT NULL",
                                                                      'name' => "VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                                      'label' => "VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL",
                                                                      'enum' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL"
                                                                      ),
                                                  'primary_key' => "id",
                                                  'auto_increment'=>'id',
                                                  'unique' => array(
                                                                       "unique_field_names_in_newsletters" => array("nid","name")
                                                                    )
                                                    );

																			
$database_structure["wpr_blog_subscription"] = array ( 'columns'=> array(
                                                                           'id' => "INT NOT NULL",
                                                                           'sid' => "INT NOT NULL",
                                                                           'type' => "enum('all','cat') NOT NULL",
                                                                           'catid' => "INT NOT NULL"
                                                                          ),
                                                       'primary_key' => "id",
                                                       'auto_increment'=>'id',
                                                       'unique' => array(
                                                                            "unique_blog_subscriptions_per_subscriber" => array('sid','type','catid')
                                                                         )
                                                     );

$database_structure["wpr_blog_series"] = array ( 'columns'=> array(
                                                                     'id' => "TINYINT NOT NULL",
                                                                      'name' => "VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                                      'catid' => "SMALLINT NOT NULL",
                                                                      'frequency' => "TINYINT NOT NULL"
                                                                   ),
                                                 'primary_key' => "id",
                                                'auto_increment'=>'id',
                                                 'unique' => array(
                                                                    "unique_names_for_blog_series" => array("name")
                                                               )
                                                );
$database_structure["wpr_autoresponder_messages"] = array ( 'columns'=> array(
                                                                               'id' => " INT NOT NULL",
                                                                               'aid' => " INT NOT NULL",
                                                                               'subject' => " text CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                                               'htmlenabled' => " TINYINT NOT NULL",
                                                                               'textbody' => " text CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                                               'htmlbody' => " text CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL",
                                                                               'sequence' => " INT NOT NULL",
                                                                               'attachimages' => " INT NOT NULL"
                                                                              ),
                                                               'primary_key' => "id",
															   'unique' => array(
                                                                    "only_one_email_for_a_day_in_followup" => array("aid","sequence")
                                                               ),
                                                               'auto_increment'=>'id'
                                                               );

$database_structure["wpr_autoresponders"] = array ( 'columns'=> array(
                                                                       'nid' => "INT NOT NULL",
                                                                       'id' => "INT NOT NULL",
                                                                       'name' => "varchar(50) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL"
                                                                      ),
                                                   'primary_key' => "id",
                                                   'auto_increment'=>'id',
                                                   'unique' => array(
                                                                        'unique_autoresponder_names_in_newsletter' => array('nid','name')
                                                                     )
                                                   );
$GLOBALS['data_structure'] = $database_structure;


$GLOBALS['admin_pages_definitions'] = array(                       
                       
                        array(
                            'page_title'=> 'New Broadcast',
                            'menu_title'=>'New Broadcast',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 1,
                            'menu_slug'=>'wpresponder/newmail.php',
                            'callback'=>'wpr_newmail'
                        ),
                        array(
                            'page_title'=> 'All Broadcasts',
                            'menu_title'=>'All Broadcasts',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 1,
                            'menu_slug'=>'wpresponder/allmailouts.php',
                            'callback'=>'wpr_all_mailouts'
                        ),
                         array(
                            'page_title'=> 'Newsletters',
                            'menu_title'=>'Newsletters',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 0,
                            'menu_slug'=>'_wpr/newsletter',
                            'callback'=>'_wpr_render_view'
                        ),
                        array(
                            'page_title'=> 'Autoresponders',
                            'menu_title'=>'Autoresponders',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 1,
                            'menu_slug'=>'wpresponder/autoresponder.php',
                            'callback'=>'wpr_autoresponder'
                        ),
                          array(
                            'page_title'=> 'Post Series',
                            'menu_title'=>'Post Series',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 1,
                            'menu_slug'=>'wpresponder/blogseries.php',
                            'callback'=>'wpr_blogseries'
                        ),
                        array(
                            'page_title'=> 'Custom Fields',
                            'menu_title'=>'Custom Fields',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 0,
                            'menu_slug'=>'_wpr/custom_fields',
                            'callback'=>'_wpr_render_view'
                        ),
                        array(
                            'page_title'=> 'Subscription Forms',
                            'menu_title'=>'Subscription Forms',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 1,
                            'menu_slug'=>'wpresponder/subscriptionforms.php',
                            'callback'=>'wpr_subscriptionforms'
                        ),               
                       

                       array(
                            'page_title'=> 'Subscribers',
                            'menu_title'=>'Subscribers',
                            'capability'=> 'manage_newsletters',
                           'legacy'   => 1,
                            'menu_slug'=> "wpresponder/subscribers.php",
                            'callback'=>'wpr_subscribers'
                        ),
                        array(
                            'page_title'=> 'Actions',
                            'menu_title'=>'Actions',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 1,
                            'menu_slug'=> "wpresponder/actions.php",
                            'callback'=>'wpr_actions'
                        ),
                        array(
                            'page_title'=> 'Settings',
                            'menu_title'=>'Settings',
                            'capability'=> 'manage_newsletters',
							'legacy'=>0,
                            'menu_slug'=> "_wpr/settings",
                            'callback'=>'_wpr_render_view'
                        ),
                        array(
                            'page_title'=> 'Import/Export Subscribers',
                            'menu_title'=>'Import/Export',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 0,
                            'menu_slug'=>'_wpr/importexport',
                            'callback'=>'_wpr_render_view'
                        ),
                        array(
                            'page_title'=> 'Background Procs',
                            'menu_title'=>'Background Procs',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 0,
                            'menu_slug'=>'_wpr/background_procs',
                            'callback'=>'_wpr_render_view'
                        ),
                        array(
                            'page_title'=> 'Queue Management',
                            'menu_title'=>'Queue Management',
                            'capability'=> 'manage_newsletters',
                            'legacy'   => 0,
                            'menu_slug'=>'_wpr/queue_management',
                            'callback'=>'_wpr_render_view'
                        )

            );










$GLOBALS['wpr_defaults'] = array(
);



$GLOBALS['wpr_cron_schedules'] = array(
									       array(
												  	'action'=> '_wpr_process_post_series',
													'schedule'=> 'every_half_hour',
													'arguments' => array()
												  ),
											array(
												  	'action'=> '_wpr_ensure_single_instances_of_crons',
													'schedule'=> 'every_half_hour',
													'arguments' => array()
												  ),
											array(
												  	'action'=> '_wpr_queue_management_cron',
													'schedule'=> 'every_ten_minutes',
													'arguments' => array()
												  ),
											array(
												  	'action'=> '_wpr_autoresponder_process',
													'schedule'=> 'every_ten_minutes',
													'arguments' => array()
												  ),
											array(
												  	'action'=> '_wpr_postseries_process',
													'schedule'=> 'every_ten_minutes',
													'arguments' => array()
												  ),
												array(
												  	'action'=> '_wpr_process_broadcasts',
													'schedule'=> 'every_ten_minutes',
													'arguments' => array()
												  ),
											array(
												  	'action'=> '_wpr_process_blog_subscriptions',
													'schedule'=> 'every_ten_minutes',
													'arguments' => array()
												  ),
											array(
												  	'action'=> '_wpr_process_queue',
													'schedule'=> 'every_ten_minutes',
													'arguments' => array()
												  ),

											 array(
												      'action' => 'wpr_cronjob',
													  'schedule'=> 'every_five_minutes',
													  'arguments' => array()
												  )
																		   
										);


foreach ($GLOBALS['wpr_cron_schedules'] as $cron) {
	$GLOBALS['_wpr_crons'][] = $cron['action'];
}

//this seems inaccurate and liable to fail
/*$GLOBALS['_wpr_crons'] = array(
							   			'_wpr_process_autoresponders',
										'_wpr_process_post_series',
										'wpr_cronjob',
										'_wpr_queue_management_cron',
										'wpr_tutorial_cron',
										"_wpr_ensure_single_instances_of_crons",
										'wpr_updates_cron',
										'wpr_send_errors'
							  );

*/
$schedules = array();
$schedules['every_five_minutes'] = array(
		 'interval'=> 300,
		 'display'=>  __('Every 5 Minutes')
		  );

$schedules['every_ten_minutes'] = array(
		 'interval'=> 600,
		 'display'=>  __('Every 10 Minutes')
		  );

$schedules['every_minute'] = array(
	 'interval'=> 60,
	 'display'=>  __('Every Minute')
	  );

$schedules ['every_half_hour'] = array(
									   'interval'=>1800,
									   'display'=>__('Every Half an Hour')
									   );

$GLOBALS['schedules'] = $schedules;

//predefined options
$initial_wpr_options = array(
					 		'_wpr_admin_notices' => base64_encode(serialize(array())),
							'wpr_hourlylimit'=> '100',
							'wpr_sent_posts' => 'off',
							'wpr_address' => ''
					 );

$GLOBALS['initial_wpr_options'] = $initial_wpr_options;


/************QUEUE MANAGEMENT****************************/
//maximum emails processed in the queue per minute
define("WPR_MAX_QUEUE_EMAILS_SENT_PER_MINUTE",100);   //DO **NOT*** SET IT TO > 500 . E-mails WILL stop delivery completely.
define("WPR_MAX_QUEUE_TABLE_SIZE",1073741824); // 1GB
define("WPR_MAX_QUEUE_DELIVERY_EXECUTION_TIME",300); //the queue delivery burst can run for a maximum of 5 minutes at a time.
define("WPR_MAX_AUTORESPONDER_PROCESS_EXECUTION_TIME",300); //the autoresponder processor can run for a maximum of 5 minutes at a time.
define("WPR_MAX_POSTSERIES_PROCESS_EXECUTION_TIME",300); //the postseries processor can run for a maximum of 5 minutes at a time.
define("WPR_MAX_NEWSLETTER_PROCESS_EXECUTION_TIME",1800); //the newsletter broadcast processor can run for a maximum of half an hour at a time.

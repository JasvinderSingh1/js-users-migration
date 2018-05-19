<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<br /><br />
<div>
	<p>
		<strong>instructions:</strong> 
		<ul>
			<li>Delete all users of wordPress site except admin</li>
			<li>Change user name of admin i.e. value in user_login column in wp_users table to a unique name being sure that it had not been used in your drupal website.</li>
			<li>Set Database name, Hostname, Port, Username and Password of drupal website. </li>
		</ul>
	</p>
</div>
<?php 
//Check form submition
if(isset($_POST['submit'])){
	if ( ! isset( $_POST['drupalPost'] ) || ! wp_verify_nonce( $_POST['drupalPost'], 'drupalSubmit' )) {
	   print 'Sorry, your nonce did not verify.';
	   exit;	
	} else {	
		$drupalDbName 	= sanitize_text_field($_POST['jw_drupalDbName']);
		$DbNameexpld 	= explode(".",$drupalDbName);
		$DbName			= $DbNameexpld[0];
		$jw_host 		= sanitize_text_field($_POST['jw_host']);
		$jw_port 		= sanitize_text_field($_POST['jw_port']);
		$jw_username 	= sanitize_text_field($_POST['jw_username']);
		$jw_password 	= sanitize_text_field($_POST['jw_password']);
		if(($drupalDbName != '' && $drupalDbName != 'drupalDBname.prefix') || $jw_host != '' || $jw_port != '' || $jw_username != '' || $jw_password != ''){
				$lwpdb 		= new wpdb( $jw_username, $jw_password, $DbName, $jw_host );
				$lwpdb->show_errors();      
				$jUser = $lwpdb->get_results( $lwpdb->prepare( "SELECT * FROM users_field_data","","") );
				global $wpdb;
				$wpdb->show_errors();
				$wpPrefix	=	$wpdb->prefix;
				if($jUser){
					foreach($jUser as $jUserVal){ 
					   //$userid 			= 	$jUserVal->uid; 
					   $user_login 		= 	$jUserVal->name;
					   $password 		= 	$jUserVal->pass;
					   $user_mail 		= 	$jUserVal->init; 
					   $created 		= 	$jUserVal->created;
					   if($user_login){

						$date_created =  date('Y-m-d H:i:s', $created);
						

						

						$wpdb->insert( 
									'wp_users', 
									array( 
							'user_login'	=> $user_login,
							'user_pass'   	=>  $password,
							'user_nicename' =>  $user_login,
							'user_email'  	=>  $user_mail,
							'user_registered' =>  $date_created,
							'user_status'  	=>  '',
							'display_name'  =>  $user_login
											), 
									array( 
											'%s', 
											'%s', 
											'%s', 
											'%s', 
											'%s',  
											'%s',
											'%s'
											) 
						);
						
						//$userid = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM wp_users WHERE user_login = '$user_login'", ARRAY_A ));
						$userid = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM wp_users WHERE user_login = %s", $user_login ) );
						$uid = $userid[0]->ID;

						//$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'nickname', 'true' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( %d, %s, %s ) ", $uid, 'nickname', $user_login ) );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'first_name', '' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'last_name', '' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'description', '' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'rich_editing', 'true' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'syntax_highlighting', 'true' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'comment_shortcuts', 'false' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'admin_color', 'fresh' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'use_ssl', '0' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'show_admin_bar_front', 'true' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'locale', '' )","","") );

						//$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'wp_capabilities', 'true' )","","") );

						$capabilities = 'a:1:{s:10:"subscriber";b:1;}';
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( %d, %s, %s ) ", $uid, 'wp_capabilities', $capabilities ) );

						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', ' 	wp_user_level', '0' )","","") );
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$uid', 'dismissed_wp_pointers', '' )","","") );

					   }
					}   
					echo '<span style="color:green;">All users inserted. Have Fun !!!</span>';
				 }  
			}
	}
}else{
		$xoopsDbName='xoopsDBname.prefix';
}
?>
<form method="post">
<table>
<tr><th>Insert drupal database name with prefix<span style="color:red;"> (ex - drupalDBname.prefix) *</span></th><td><input type="text" name="jw_drupalDbName" id="jw_drupalDbName" onfocus="this.value=='drupalDBname.prefix'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='drupalDBname.prefix':this.value=this.value;" value="<?php if(isset($drupalDbName)) { echo $drupalDbName; } ?>" maxlength="50"></td></tr>
<tr><th>Hostname <span style="color:red;">*</span></th><td><input type="text" name="jw_host" id="jw_host" onfocus="this.value=='Hostname'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Hostname':this.value=this.value;" value="<?php if(isset($jw_host)) { echo $jw_host; } ?>" maxlength="100"></td></tr>
<tr><th>Port <span style="color:red;">*</span></th><td><input type="text" name="jw_port" id="jw_port" onfocus="this.value=='Port'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Port':this.value=this.value;" value="<?php if(isset($jw_port)) { echo $jw_port; } ?>" maxlength="100"></td></tr>
<tr><th>Username <span style="color:red;">*</span></th><td><input type="text" name="jw_username" id="jw_username" onfocus="this.value=='Username'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Username':this.value=this.value;" value="<?php if(isset($jw_username)) { echo $jw_username; } ?>" maxlength="100"></td></tr>
<tr><th>Password <span style="color:red;">*</span></th><td><input type="password" name="jw_password" id="jw_password" onfocus="this.value=='Password'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Password':this.value=this.value;" value="<?php if(isset($jw_password)) { echo $jw_password; } ?>" maxlength="100">
<?php wp_nonce_field( 'drupalSubmit', 'drupalPost' ); ?>
</td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Migrate users"></td></tr>
</tr>
</table>
</form>
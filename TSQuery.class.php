<?php
/* Author    : Max Rath
 * Homepage  : http://maxesstuff.de
 * Email     : drak3@maxesstuff.de
 * License   : See license-folder
 * Version   : See changelog.txt
 */

class TSQuery {

        private $connection;
        protected $sock_error;
        protected $sock_error_string;
        protected $loggedin = false;
        protected $actual_vserver;


        protected $caching;
        protected $last_cached;
        public    $cachetime;
        protected $standard_cachetime;
        protected $cachepath;


        private $ip;
        private $query_port;
        private $timeout;
        private $ftid;
        private $ftconn;
        
        private $cmds;
        private $cmd_sent;

        //In the constructor will be created a new socket and login stuff will be done.

        /**
                The consructor opens a new Connection to the host and logs in with name and password



                @throws Exception throws if connection or login failed.

                @param	string	$host: the hostname or the ip-adress e.g. "localhost" or "127.0.0.1"
                @param	int		$port: the server query port e.g. 10011 (standart query port)
                @param 	int 	$vserver: the virtual server to login e.g. 0
                @param 	string	$loginname: with this name you will be logged in (make sure that if you're no logged in with serveradmin some functions don't work)
                @param	string	$password: a valid Password connected to the loginname



        */
        function __construct($host,$port) {

                //open Connection and check for errors
                $this->cachepath = s_root."/cache/".$host.$port."/";
                $this->ip = gethostbyname($host);
                $this->query_port = $port;
                $this->timeout = 5;
                $this->actual_vserver = NULL;
               
                $this->open_new_connection();
                $this->ftconn = NULL;
                $this->caching = false;
                $this->standard_cachetime = 5;

                if($this->connection == NULL || $this->connection == false) {
                        if(false) {
                                throw new Exception("Connection to Server $this->ip on port $this->port");
                        }
                        else {
                                throw new Exception("Server is Offline at the Moment");
                        }
                }

                // Read the TS3 sequence
                fread($this->connection,6);
                $this->ftid = 0;

        }


        function set_caching($caching,$standard_cachetime=NULL,$cachetime=NULL) {
            $this->caching = $caching;
            if($standard_cachetime != NULL)
                $this->standard_cachetime = $standard_cachetime;
            if(is_array($cachetime))
                $this->cachetime = $cachetime;

        }

        //WRAPPER\\
        /**
                Wrapper for the Querycommand use with port=$port i had to choos another name
                so the function is called use_vserver
                @acces public

                @param integer $port: is a valid port of a Virtual server

                @return array|boolean	parsed response of the query if port is an integer, if not an integer: false
        **/
        public function use_by_port($port) {
                if(is_numeric($port)) {

                        $resp = $this->send_cmd("use port=".$port);
                        if($resp['error']['id'] === 0) {
                            $this->cachepath .= $port."/";
                        }
                        return $resp;

                }
                return false;
        }

        public function use_by_id($id) {
                if(is_numeric($id)) {
                        return $this->send_cmd("use sid=".$id);
                }
                return false;
        }
        
        public function logout() {
            $this->send_cmd("logout");
        }

        public function quit() {
                return $this->send_cmd("quit");
        }

        public function login($username, $password) {

                $username = $this->ts3query_escape($username);
                $password = $this->ts3query_escape($password);
                return $this->send_cmd("login client_login_name=".$username." client_login_password=".$password);

        }

        public function serverinfo($caching=true) {
                $ret = $this->send_cmd("serverinfo",$caching);
                if($ret == false)
                    return false;

                $ret['return'] = $this->ts3_to_hash($ret['return']);
                return $ret;
        }

        /*
         * $i = -1;
                $ret = '';
                if($this->connection === NULL) {
                        $this->open_new_connection();
                }
                stream_set_timeout($this->connection, 0, 300000);
                fputs($this->connection,$text);

                do {
                        $ret .=  fgets($this->connection,8096);
                } while(strstr($ret,"error id=") === false);

                return $ret;
         */

        public function download($path, $cid, $cpw="", $seek=0) {

            $this->ftid++;
            $cmd = "ftinitdownload clientftfid=$this->ftid name=".$this->ts3query_escape($path)." cid=".intval($cid)." cpw=".$this->ts3query_escape($cpw)." seekpos=".intval($seek);
            $ret = $this->send_cmd($cmd);
            if($ret['error']['id'] != 0)
                return false;
            $ret = $this->ts3_to_hash($ret['return']);
            $key = $this->ts3query_unescape($ret['ftkey']);
            $size = $ret['size'];
            
            if($this->ftconn == NULL)
                    $this->ftconn = fsockopen($this->ip, $ret['port']);
            if($this->ftconn == false)
                return false;

            fputs($this->ftconn,$key);
            $downloaded = 0;
            $download ="";
            while($downloaded < $size - $seek) {
                $akt = fgets($this->ftconn,8096);
                $downloaded += strlen($akt);
                $download .= $akt;
            }
            return $download;

        }





        public function channellist($options="",$caching=true) {
                if($this->are_options($options)) {
                        $ret = $this->send_cmd("channellist ".$options,$caching);
                        if($ret == false)
                                return false;
                        if($ret['error']['id'] == 0) {
                                $ret['return']  = $this->ts3_to_hash(explode("|",$ret['return']));
                                return $ret;
                        }
                        return false;
                }
                return false;
        }

        public function clientlist($options="",$caching=true) {
                if($this->are_options($options)) {
                        $ret = $this->send_cmd("clientlist ".$options,$caching);
                        if($ret == false)
                                return false;
                        if( $ret['error']['id'] == 0) {
                                $ret['return'] = $this->ts3_to_hash(explode("|",$ret['return']));
                                return $ret;
                        }
                        return false;
                }
        }


        public function servergrouplist($caching=true) {
                $ret = $this->send_cmd("servergrouplist",$caching);
                $ret['return'] = $this->ts3_to_hash(explode("|",$ret['return']));
                return $ret;
        }

        public function channelgrouplist($caching=true) {
                $ret = $this->send_cmd("channelgrouplist",$caching);
                $ret['return'] = $this->ts3_to_hash(explode("|",$ret['return']));
                return $ret;
        }

        public function clientinfo($clid,$caching=TRUE) {

                $ret = $this->send_cmd("clientinfo clid=".$clid,$caching);
                $ret['return'] = $this->ts3_to_hash($ret['return']);
                return $ret;
        }







        //TOOLS\\


        /*
                This function parses a response given by the query the
                @acces protected

                @param	string	$response: a response of a TS3 Query (example response: "helpfilestuff error id=0 msg=nothing\susefull"


                @return array	it's split into ['return'] (in this example "helpfilestuff") ['error']['id'] (here zero) and ['error']['msg']
                                                (here "nothing usefull") Note that ['error']['msg'] is escaped by @see ts3_response_escape()

        */

        protected function parse_ts3_response($response) {
                $result = preg_match("#.*error id=([[:digit:]]{1,4}) msg=(.*)$#Ds",$response,$buff);
                if($result == 0) {
                        $ret['return'] = $response;
                        $ret['error']  = false;

                }
                else{
                        $ret['return'] = preg_replace("#error id=[[:digit:]]{1,4} msg=.*$#Ds",'',$response);
                        $ret['error']['id'] = (int) $buff[1];
                        $ret['error']['msg'] = $this->ts3query_unescape($buff[2]);
                }
                return $ret;
        }


        public function ts3_to_hash($ts3) {
                if(is_array($ts3)) {
                        foreach($ts3 as $key=>$value) {
                                $ret[$key] = $this->ts3_to_hash($value);
                        }
                }
                else {
                        $pairs = explode(" ",trim($ts3));
                        foreach($pairs as $pair) {
                                if(@strpos($pair,"=",2) !== false) {
                                        $pair = explode("=",$pair,2);
                                        $ret[$pair[0]] = $this->ts3query_unescape($pair[1]);
                                }
                                else{
                                        $ret[$pair] = false;
                                }
                        }
                }
                return $ret;
        }


        /*public function ts3_clientlist_delqueryclient($clientlist) {
                foreach($clientlist as $client) {
        */


        public function ts3query_escape($text) {
                $to_escape = 		Array( "\\"  , "/"    ,  "\n"  , " "   , "|"   , "\a"  , "\b"  , "\f"  , "\n"  , "\r"  , "\t"  , "\v" );
                $replace_with = 	Array("\\\\" , "\/" ,  "\\n" , "\\s" , "\\p" , "\\a" , "\\b" , "\\f" , "\\n" , "\\r" , "\\t" , "\\v");
                return str_replace($to_escape , $replace_with , $text);
        }

        public function ts3query_unescape($text) {
                if(is_numeric($text))
                        return (int) $text;
                $to_unescape  = 	Array("\\/" , "\\\\\\" ,  "\\n" , "\\s" , "\\p" , "\\a" , "\\b" , "\\f" , "\\n" , "\\r" , "\\t" , "\\v");
                $replace_with = 	Array("/"   , "\\"     ,  "\n"  , " "   , "|"   , "\a"  , "\b"  , "\f"  , "\n"  , "\r"  , "\t"  , "\v" );
                return str_replace($to_unescape , $replace_with , $text);
        }

        public function are_options($options) {
                if($options == "") {
                        return true;
                }
                if(preg_match("/^[[:alpha:] -]*$/D",$options)) {
                        return true;
                }
                return false;

        }



        public function send_cmd($cmd,$caching=false) {

                if(preg_match("/[\r\n]/", $cmd))
                        return false;
               
                if($caching == true && file_exists($this->cachepath."query/".$cmd) && !$this->cache_expired($cmd) && $this->caching == true) {
                      return $this->parse_ts3_response(file_get_contents($this->cachepath."query/".$cmd));
                }
                if($this->caching == TRUE && $caching == FALSE && !$this->cmd_sent) {
                    $this->cmds[] = $cmd;
                    return $this->parse_ts3_response("error id=0 msg=ok");
                }
                foreach($this->cmds as $key=>$command) {
                    $this->send_raw($cmd."\n");
                    ts3_check($this->send_raw($command."\n"), $command);
                    unset ($this->cmds[$key]);
                }
                $this->cmd_sent = TRUE;
                $response = $this->send_raw($cmd."\n");
                if($response === false) {
                        return false;
                }
                if($caching == true && $this->caching == true) {
                    file_put_contents($this->cachepath."query/time/$cmd", time());
                    file_put_contents($this->cachepath."query/$cmd", $response);
                }

                $response = $this->parse_ts3_response($response);
                return $response;

        }

        protected function cache_expired($cmd) {
            if(!file_exists($this->cachepath."query/time/$cmd"))
                    return true;
            if(!empty($this->last_cached[$cmd])) {
                if(time() - $this->last_cached[$cmd] < ( isset($this->cachetime[$cmd]) ? $this->cachetime[$cmd] : $this->standard_cachetime ) )
                       return false;
            }
            else {
                $this->last_cached[$cmd] = file_get_contents($this->cachepath."query/time/$cmd");
                if(time() - $this->last_cached[$cmd] < ( isset($this->cachetime[$cmd]) ? $this->cachetime[$cmd] : $this->standard_cachetime ) )
                       return false;
            }
            return true;
        }

        private function send_raw($text) {
                $i = -1;
                $ret = '';
                if($this->connection === NULL) {
                        $this->open_new_connection();
                }
                stream_set_timeout($this->connection, 0, 300000);
                fputs($this->connection,$text);

                do {
                        $ret .=  fgets($this->connection,8096);
                } while(strstr($ret,"error id=") === false);

                return $ret;


        }

        private function open_new_connection() {

                $this->connection = fsockopen($this->ip,$this->query_port,$this->sock_error,$this->sock_error_string,$this->timeout);
                if($this->sock_error != 0) {
                        die( "Can't open connection");
                }
        }




}
		
		
		
			
			
		
		
			
				

			
		
		
		
		
		
		
		
			

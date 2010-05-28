<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}

global $CURUSER, $tracker_lang, $ss_uri,$CACHEARRAY;
if ($CURUSER) {
    $content = '';

    $id = sql_query("SELECT id FROM polls ORDER BY id DESC LIMIT 1");
    $id = @mysql_result($id,0);
    
      if (!$id) {$content .="<h1>��� �������!</h1>"; } else {
		
		      $pquestion = array();
   $pstart = array();
   $pexp = array();
   $public = array();
   $sidvalues = array();
   $votes = array();
   $sids = array();
   $votesres = array();
   $sidcount = array();
   $sidvals = array();
   $votecount = array();
   $usercode = array();

        if (!defined("CACHE_REQUIRED")){
 	require_once($rootpath . 'classes/cache/cache.class.php');
	require_once($rootpath .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

      $pollres = $cache->get('block-polls', 'query', $CACHEARRAY['polls_lastupdate']);
     if($pollres===false){
   $poll = sql_query("SELECT polls.*, polls_structure.value, polls_votes.sid,polls_votes.vid,polls_votes.user,users.username,users.class FROM polls_votes LEFT JOIN users ON polls_votes.user=users.id LEFT JOIN polls_structure ON polls_votes.sid=polls_structure.id LEFT JOIN polls ON polls.id = polls_structure.pollid WHERE polls.id = $id ORDER BY sid ASC");
   
   while ($pollarray = mysql_fetch_array($poll))
     $pollres[] = $pollarray;
     
    $cache->set('block-polls', 'query', $pollres);
                        sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='polls_lastupdate'");
}

   foreach($pollres as $pollarray) {
     $pquestion[] = $pollarray['question'];
     $pstart[] = $pollarray['start'];
     $pexp[] = $pollarray['exp'];
     $public[] = $pollarray['public'];
     $sidvalues[$pollarray['sid']] = $pollarray['value'];
     $votes[] = array($pollarray['sid'] => array('vid'=>$pollarray['vid'],'userid'=>$pollarray['user'],'username'=>$pollarray['username'],'userclass'=>$pollarray['class']));
     $sids[] = $pollarray['sid'];
      }

      $pstart = @array_unique($pstart);
      $pstart = $pstart[0];
      $pexp = @array_unique($pexp);
      $pexp = $pexp[0];
      $pquestion = @array_unique($pquestion);
      $pquestion = $pquestion[0];
      $public = @array_unique($public);
      $public = $public[0];

      $sids = @array_unique($sids);
      sort($sids);
      reset($sids);


    $content .= '<table width="100%" border="1"><tr><td>����� � '.$id.'</td><td>������: '.get_date_time($pstart).(!is_null($pexp)?", ������������� ".get_date_time($pexp):"").'</td></tr><tr><td class="colhead">'.$pquestion.'</td><td class="colhead">'.((get_user_class() >= UC_ADMINISTRATOR)?"[<a href=\"pollsadmin.php?action=edit&id=$id\">�������������</a>][<a onClick=\"return confirm('�� �������?')\" href=\"pollsadmin.php?action=delete&id=$id\">�������</a>]":"").'</td></tr>';

   foreach ($sids as $sid)
   $votesres[$sid] = array();

   $voted=0;

   foreach($votes as $votetemp)
   foreach ($votetemp as $sid => $value)
   array_push($votesres[$sid],$value);




   foreach ($votesres as $votedrow => $votes) {

     $sidcount[] = $votedrow;
     $sidvals[] = $sidvalues[$votedrow];
         $votecount[$votedrow] = 0;
     $usercode[$votedrow] = '';

     foreach($votes as $vote) {
  //     print $votedrow."<hr>";
//   print_r ($vote);
    $vid=$vote['vid'];
    $userid=$vote['userid'];
    $user['username']=$vote['username'];
    $user['class']=$vote['userclass'];

//      print($vote['vid'].$vote['username'].$vote['userclass'].$vote['userid'].",");
     if ($vote['userid'] == $CURUSER['id']) $voted = $votedrow;
     $votecount[$votedrow]++;

          if (($public == 'yes') || (get_user_class() >= UC_MODERATOR))
     $usercode[$votedrow] .= "<a href=\"userdetails.php?id=$userid\">".get_user_class_color($user['class'],$user['username'])."</a>".((get_user_class() >= UC_MODERATOR)?" [<a onClick=\"return confirm('������� ���� �����?')\" href=\"polloverview.php?deletevote&vid=".$vid."\">D</a>] ":" ");

   if (($votecount[$votedrow]) >= $maxvotes) $maxvotes = $votecount[$votedrow];

 }
 }     $tvotes = array_sum($votecount);

 @$percentpervote = round(50/$maxvotes);
 if (!$percentpervote) $percentpervote=0;

 foreach ($sidcount as $sidkey => $vsid){
   @$percent = round($votecount[$vsid]*100/($tvotes));
   if (!$percent) $percent = 0;
 $content .="<tr><td width=\"250px\">";
  if ($vsid == $voted)
  $content .="<b>".$sidvals[$sidkey]." - ��� �����</b>";
  elseif (((!is_null($pexp) && ($pexp > time())) || is_null($pexp)) && !$voted) $content .="<form name=\"voteform\" method=\"post\" action=\"polloverview.php?vote&id=$id\"><input type=\"radio\" name=\"vote\" value=\"$vsid\"><input type=\"hidden\" name=\"type\" value=\"$ptype\">".$sidvals[$sidkey];
  else $content .=$sidvals[$sidkey];
  $content .="</td><td><img src=\"./themes/$ss_uri/images/bar_left.gif\"><img src=\"./themes/$ss_uri/images/bar.gif\" height=\"12\" width=\"".$percentpervote*$votecount[$vsid]."%\"><img src=\"./themes/$ss_uri/images/bar_right.gif\">$percent%, �������: ".$votecount[$vsid]."</td></tr>";
}
  if (((!is_null($pexp) && ($pexp > time())) || is_null($pexp)) && !$voted) $content .="<tr><td><input type=\"submit\" value=\"���������� �� ���� �������!\"></form></td>";
  elseif (!is_null($pexp) && ($pexp < time())) $content .='<tr><td>����� ������</td>';
  elseif ($voted) $content .='<tr><td>�� ��� ���������� � ���� ������</td>';
  $content .='<td align="center">����� �������: '.$tvotes.' [<a href="polloverview.php?id='.$id.'"><b>���������</b></a>] [<a href="pollsarchive.php"><b>����� �������</b></a>]</td></tr>';

 $content .= "</table>";
 }


 } else $content = "<div align=\"center\"><h1>�������, ����� ������������ � ������</h1></div>";

?>
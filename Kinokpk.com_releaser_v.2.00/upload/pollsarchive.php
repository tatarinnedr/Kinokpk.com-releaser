<?php

/*
Project: Kinokpk.com releaser
This file is part of Kinokpk.com releaser.
Kinokpk.com releaser is based on TBDev,
originally by RedBeard of TorrentBits, extensively modified by
Gartenzwerg and Yuna Scatari.
Kinokpk.com releaser is free software;
you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
Kinokpk.com is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Kinokpk.com releaser; if not, write to the
Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
MA  02111-1307  USA
Do not remove above lines!
*/


require "include/bittorrent.php";
dbconn(false);

loggedinorreturn();


  $addparam = $_SERVER['QUERY_STRING'];

   if ($addparam != "")
    $addparam = $addparam . "&" . $pagerlink;
 else
    $addparam = $pagerlink;
        list($pagertop, $pagerbottom, $limit) = pager(10, $count, "polloverview.php?" . $addparam);
        
   $pollsrow = sql_query("SELECT id FROM polls ORDER BY id DESC $limit");

    stdhead("����� �������");
    
  while (list($id) = mysql_fetch_array($pollsrow)) {

   $poll = sql_query("SELECT polls.*, polls_structure.value, polls_votes.sid,polls_votes.vid,polls_votes.user,users.username,users.class FROM polls_votes LEFT JOIN users ON polls_votes.user=users.id LEFT JOIN polls_structure ON polls_votes.sid=polls_structure.id LEFT JOIN polls ON polls.id = polls_structure.pollid WHERE polls.id = $id ORDER BY sid ASC");
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
   
   while ($pollarray = mysql_fetch_array($poll)) {
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
          if (!$pstart) stderr($tracker_lang['error'], "������ ������ �� ����������");
      $pexp = @array_unique($pexp);
      $pexp = $pexp[0];
      $pquestion = @array_unique($pquestion);
      $pquestion = $pquestion[0];
      $public = @array_unique($public);
      $public = $public[0];

      $sids = @array_unique($sids);
      sort($sids);
      reset($sids);


    print('<hr/><hr/><table width="100%" border="1"><tr><td>����� � '.$id.'</td><td>������: '.get_date_time($pstart).(!is_null($pexp)?", ������������� ".get_date_time($pexp):"").'</td></tr><tr><td class="colhead">'.$pquestion.'</td><td class="colhead">'.((get_user_class() >= UC_ADMINISTRATOR)?"[<a href=\"pollsadmin.php?action=edit&id=$id\">�������������</a>][<a onClick=\"return confirm('�� �������?')\" href=\"pollsadmin.php?action=delete&id=$id\">�������</a>]":"").'</td></tr>');

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
  print("<tr><td width=\"250px\">");
  if ($vsid == $voted)
  print("<b>".$sidvals[$sidkey]." - ��� �����</b>");
  else print($sidvals[$sidkey]);
  print("</td><td><img src=\"./themes/$ss_uri/images/bar_left.gif\"><img src=\"./themes/$ss_uri/images/bar.gif\" height=\"12\" width=\"".$percentpervote*$votecount[$vsid]."%\"><img src=\"./themes/$ss_uri/images/bar_right.gif\">$percent%, �������: ".$votecount[$vsid]."<br/>".((!$usercode[$vsid])?"����� �� ���������":$usercode[$vsid])."</td></tr>");
}
  print('<tr><td>����� ��������� � ������, ����������� ���������</td>');
  print("<td><h1>����� �������: $tvotes</h1></td></tr>");

print ('</table>');
}
 stdfoot();

?>
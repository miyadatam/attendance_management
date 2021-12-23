<script>
export default {
  methods: {
    role(role){
      if(role == 1){
        return '管理者'
      }else{
        return '一般ユーザー'
      }
    },
    csrfToken(){
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    authId(){
      return document.querySelector('meta[name="user-id"]').getAttribute('content')
    },
    sliceTime(time){
      return time.slice(0, 5)
    },
    timeChange(time){
      if(time.length == 1){
        return '0' + time
      }else{
        return time
      }
    },
    workTime(start_at, end_at, break_time){
      if(start_at && end_at){

        var y = new Date().getFullYear()
        var m = new Date().getMonth() + 1
        var d = new Date().getDate()

        var ymd = `${y}/${m}/${d}`

        var start = new Date(ymd + ' ' + start_at).getTime()
        var end = new Date(ymd + ' ' + end_at).getTime()

        if(start > end){
          var d = new Date().getDate() + 1
          ymd = `${y}/${m}/${d}`
          var end = new Date(ymd + ' ' + end_at).getTime()
        }
        var Ms = Math.abs( start - end );

        var h = ''
        var m = ''

        h = Ms / 3600000
        m = (Ms - h * 3600000) / 6000

        var break_time = break_time != 100 ? Number(break_time) : 0

        return Math.round((h + m - break_time) * 100) / 100
      }

      return ''
    },
    midnightWorkTime(start_at, end_at){
      var midnight_time = 0
      if(start_at && end_at){

      }
      // public function midnightTime(){
      //   // 深夜時間
      //   $midnight_time = 0;
      //
      //   if(!$this->activeCommuteWorkStamps->isEmpty() && !$this->activeLeaveWorkStamps->isEmpty()){
      //     // 出勤時間
      //     $commute_time = $this->activeCommuteWorkStamps[0]->attendance_time ?? $this->activeShifts[0]->commute_time;
      //     // 退勤時間
      //     $leave_time = $this->activeLeaveWorkStamps[0]->attendance_time ?? $this->activeShifts[0]->leave_time;
      //     if($commute_time && $leave_time){
      //       $commute_time = new Carbon(date('Y-m-d') . ' ' . $commute_time);
      //       $leave_time = new Carbon(date('Y-m-d') . ' ' . $leave_time);
      //       // 退勤時間のほうが出勤時間よりも早い = 日をまたいでいるので 退勤時間に+1日する
      //       $leave_time = $leave_time < $commute_time ? $leave_time->addDay() : $leave_time;
      //       // 22時
      //       $time22 = new Carbon(date('Y-m-d') . ' ' . '22:00');
      //       // 29時(次の日の朝5時)
      //       $time29 = new Carbon(date('Y-m-d', strtotime('+1 day')) . ' ' . '05:00');
      //       // 出勤時間から退勤時間までループ
      //       $time = $commute_time;
      //       while(1){
      //         // 一時間足す
      //         $time->addHours(1);
      //         if($time22 < $time && $time <= $time29){
      //           $midnight_time++;
      //         }
      //         // 退勤時間を超えたら残りの「分」の計算だけ行い終了
      //         if($leave_time < $time){
      //           if($time22 < $leave_time && $leave_time <= $time29){
      //             $midnight_time -= round((strtotime($time) - strtotime($leave_time)) / 3600, 2);
      //           }
      //           break;
      //         }
      //       }
      //     }
      //   }
      //   return $midnight_time;
      // }
    },
    approve(approve){
      if(approve == 0){
        return '承認待ち'
      }else if(approve == 1){
        return '承認済み'
      }else if(approve == 2){
        return '未承認'
      }
    },
    approveClass(approve){
      if(approve == 0){
        return ''
      }else if(approve == 1){
        return 'text-success'
      }else if(approve == 2){
        return 'text-danger'
      }
    },
    errorCheck(res){
      if(res.error){
        window.location = '/error'
      }
    },
  }
}
</script>

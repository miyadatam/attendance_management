<script>
export default {
  methods: {
    userShowLink(user_id){
      return '/user/' + user_id
    },
    attendanceShowLink(user_id, year='', month=''){
      if(year && month){
        return `/attendance/${user_id}/${year}/${month}`
      }else{
        return '/attendance/' + user_id
      }
    },
    // シフト
    shiftShowLink(user_id, date = ''){
      if(date){
        return '/shift/' + user_id + '/' + date
      }else{
        return '/shift/' + user_id
      }
    },
    shiftCreateLink(date){
      return '/shift/create/' + date
    },
    shiftEditLink(shift_id){
      return '/shift/' + shift_id + '/edit'
    },
    shiftAction(shift_id){
      return '/shift/' + shift_id
    },
    shiftApproveAction(shift_id){
      return '/shift/approve/' + shift_id
    },
    // 打刻
    workStampShowLink(user_id, date = ''){
      if(date){
        return '/work_stamp/' + user_id + '/' + date
      }else{
        return '/work_stamp/' + user_id
      }
    },
    workStampCreateLink(date){
      return '/work_stamp/create/' + date
    },
    workStampEditLink(work_stamp_id){
      return '/work_stamp/' + work_stamp_id + '/edit'
    },
    workStampAction(work_stamp_id){
      return '/work_stamp/' + work_stamp_id
    },
    workStampApproveAction(work_stamp_id){
      return '/work_stamp/approve/' + work_stamp_id
    },
    // 早出
    earlyTimeShowLink(user_id, date = ''){
      if(date){
        return '/earlytime/' + user_id + '/' + date
      }else{
        return '/earlytime/' + user_id
      }
    },
    earlyTimeCreateLink(date){
      return '/earlytime/create/' + date
    },
    earlyTimeEditLink(earlytime_id){
      return '/earlytime/' + earlytime_id + '/edit'
    },
    earlyTimeAction(earlytime_id){
      return '/earlytime/' + earlytime_id
    },
    earlyTimeApproveAction(earlytime_id){
      return '/earlytime/approve/' + earlytime_id
    },

    // 残業
    overTimeShowLink(user_id, date = ''){
      if(date){
        return '/overtime/' + user_id + '/' + date
      }else{
        return '/overtime/' + user_id
      }
    },
    overTimeCreateLink(date){
      return '/overtime/create/' + date
    },
    overTimeEditLink(overtime_id){
      return '/overtime/' + overtime_id + '/edit'
    },
    overTimeAction(overtime_id){
      return '/overtime/' + overtime_id
    },
    overTimeApproveAction(overtime_id){
      return '/overtime/approve/' + overtime_id
    },
  }
}
</script>

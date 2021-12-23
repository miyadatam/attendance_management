<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">打刻申請編集</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(authId())">Home</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="attendanceShowLink(authId())">出勤簿</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="workStampShowLink(authId(), date)">打刻申請一覧</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">打刻申請編集</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ dateFormat }}</h4>
            <div class="my-3 font-weight-bold text-center">シフト {{ shiftStatus }}</div>
            <p class="text-danger font-weight-bold text-center" v-if="is_error">入力不備があります</p>
            <form :action="workStampAction(pending_work_stamp.id)" method="post" class="mt-3" id="form" ref="form">
              <input type="hidden" name="_token" :value="csrfToken()">
              <input type="hidden" name="_method" value="PUT">
              <div class="form-group">
                <label for="is-start">区分</label>
                <select class="form-control col-md-4" id="is-start" disabled>
                  <option v-if="is_start">出勤</option>
                  <option v-else>退勤</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exception">項目</label>
                <select type="text" name="exception" class="form-control col-md-4" id="exception" v-model="exception" @change="submitInit">
                  <option value="0"></option>
                  <option value="1" v-if="is_start">遅刻</option>
                  <option value="1" v-else>早退</option>
                </select>
              </div>
              <div class="form-group" v-show="exception == 1">
                <label for="stamp-at" v-if="is_start">出勤時間</label>
                <label for="stamp-at" v-else>退勤時間</label>
                <input type="time" name="stamp_at" class="form-control col-md-4" id="stamp-at" v-model="stamp_at" :disabled="exception != 1" @change="submitInit()">
              </div>
              <div class="form-group">
                <label for="memo">備考</label>
                <textarea name="memo" class="form-control" id="memo" :placeholder="placeholder" v-model="memo" @input="submitInit()"></textarea>
              </div>
            </form>
            <div class="text-right">
              <input type="hidden" name="date" :value="date" form="form">
              <a :href="workStampShowLink(authId(), date)" class="btn btn-gradient-light mr-2">Back</a>
              <button type="button" class="btn btn-gradient-success" form="form" @click="submit">Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Data from '../common/Data.vue'
import Link from '../common/Link.vue'

export default {
  props: ['date', 'shift', 'pending_work_stamp'],
  mixins: [Data, Link],
  data(){
    return {
      'is_start': this.pending_work_stamp.is_start,
      'stamp_at': this.pending_work_stamp.stamp_at,
      'exception': this.pending_work_stamp.exception,
      'memo': this.pending_work_stamp.memo,
      'is_error': false
    }
  },
  mounted(){

  },
  computed: {
    placeholder(){
      if(this.exception == 0){
        return '申請理由'
      }else if(this.is_start == 1){
        return '遅刻理由'
      }else{
        return '早退理由'
      }
    },
    dateFormat(){
      var D = new Date(this.date)
      var y = D.getFullYear()
      var m = D.getMonth() + 1
      var d = D.getDate()

      return `${y}年${m}月${d}日`

    },
    shiftStatus(){
      if(this.shift.item == 1){
        return `稼働 ${this.sliceTime(this.shift.start_at)} ~ ${this.sliceTime(this.shift.end_at)}`
      }else if(this.shift.item == 2){
        return '公休'
      }else{
        return '有給'
      }
    },
  },
  methods: {
    submit(){
      this.submitInit()
      this.is_error = false

      if(this.exception == 1 && document.getElementById('stamp-at').value == ''){
        document.getElementById('stamp-at').classList.add('border-danger')
        this.is_error = true
      }

      if(document.getElementById('memo').value == ''){
        document.getElementById('memo').classList.add('border-danger')
        this.is_error = true
      }
      if(!this.is_error){
        this.$refs.form.submit()
      }
    },
    submitInit(){
      if(document.getElementById('stamp-at').classList.contains('border-danger') == true){
        document.getElementById('stamp-at').classList.remove('border-danger')
      }
      if(document.getElementById('memo').classList.contains('border-danger') == true){
        document.getElementById('memo').classList.remove('border-danger')
      }
    }
  },
}
</script>

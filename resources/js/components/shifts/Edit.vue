<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">シフト申請</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(authId())">Home</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="attendanceShowLink(authId())">出勤簿</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="shiftShowLink(authId(), date)">シフト申請一覧</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">シフト申請編集</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ dateFormat }}</h4>
            <p class="text-danger font-weight-bold text-center" v-if="is_error">入力不備があります</p>
            <form :action="shiftAction(pending_shift.id)" method="post" class="mt-3" id="form" ref="form">
              <input type="hidden" name="_token" :value="csrfToken()">
              <input type="hidden" name="_method" value="PUT">
              <div class="form-group">
                <label for="item">区分</label>
                <select type="text" name="item" class="form-control col-md-4" id="item" v-model="select_item">
                  <option v-for="(item, value) in items" :value="value">{{ item }}</option>
                </select>
              </div>
              <div class="form-row" v-show="select_item == 1">
                <div class="form-group col-sm-6">
                  <label for="start-at">出勤時間</label>
                  <input type="time" name="start_at" class="form-control" id="start-at" :value="pending_shift.start_at" :disabled="select_item != 1">
                </div>
                <div class="form-group col-sm-6">
                  <label for="end-at">退勤時間</label>
                  <input type="time" name="end_at" class="form-control" id="end-at" :value="pending_shift.end_at" :disabled="select_item != 1">
                </div>
              </div>
              <div class="form-group">
                <label for="memo">備考</label>
                <textarea name="memo" class="form-control" id="memo" :value="pending_shift.memo" placeholder="申請理由"></textarea>
              </div>
            </form>
            <div class="text-right">
              <input type="hidden" name="date" :value="date" form="form">
              <a :href="shiftShowLink(authId(), date)" class="btn btn-light mr-2">Back</a>
              <button type="button" class="btn btn-gradient-success" form="form" @click="submitInit(), submit()">Update</button>
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
  props: ['date', 'pending_shift'],
  mixins: [Data, Link],
  data(){
    return {
      'items': {
        0: '',
        1: '稼働',
        2: '公休',
        3: '有給'
      },
      'select_item': this.pending_shift.item,
      'is_error': false
    }
  },
  computed: {
    dateFormat(){
      var D = new Date(this.date)
      var y = D.getFullYear()
      var m = D.getMonth() + 1
      var d = D.getDate()

      return `${y}年${m}月${d}日`

    },
    shiftItem(){
      if(this.pending_shift.item == 1){
        return '稼働'
      }else if(this.pending_shift.item == 2){
        return '公休'
      }else if(this.pending_shift.item == 3){
        return '有給'
      }
    },
  },
  methods: {
    submit(){
      this.is_error = false
      if(this.select_item == 0){
        document.getElementById('item').classList.add('border-danger')
        this.is_error = true
      }else if(this.select_item == 1){
        if(document.getElementById('start-at').value == ''){
          document.getElementById('start-at').classList.add('border-danger')
          this.is_error = true
        }
        if(document.getElementById('end-at').value == ''){
          document.getElementById('end-at').classList.add('border-danger')
          this.is_error = true
        }
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

      if(document.getElementById('item').classList.contains('border-danger') == true){
        document.getElementById('item').classList.remove('border-danger')
      }
      if(document.getElementById('start-at').classList.contains('border-danger') == true){
        document.getElementById('start-at').classList.remove('border-danger')
      }
      if(document.getElementById('end-at').classList.contains('border-danger') == true){
        document.getElementById('end-at').classList.remove('border-danger')
      }
      if(document.getElementById('memo').classList.contains('border-danger') == true){
        document.getElementById('memo').classList.remove('border-danger')
      }
    }
  },
}
</script>

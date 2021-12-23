<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">早出申請</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(authId())">Home</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="attendanceShowLink(authId())">出勤簿</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="earlyTimeShowLink(authId(), date)">早出申請一覧</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">早出申請</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ dateFormat }}</h4>
            <div class="text-center mt-2 mb-4 font-weight-bold">
              <div>出勤時間</div>
              <div>{{ workStampStatus }}</div>
            </div>
            <p class="text-danger font-weight-bold text-center" v-if="is_error">入力不備があります</p>
            <form action="/earlytime" method="post" class="mt-3" id="form" ref="form">
              <input type="hidden" name="_token" :value="csrfToken()">
              <div class="form-row">
                <div class="form-group col-sm-6">
                  <label for="start-at">開始時間</label>
                  <input type="time" name="start_at" class="form-control" id="start-at" @change="submitInit()">
                </div>
                <div class="form-group col-sm-6">
                  <label for="end-at">終了時間</label>
                  <input type="time" name="end_at" class="form-control" id="end-at" @change="submitInit()">
                </div>
              </div>
              <div class="form-group">
                <label for="memo">備考</label>
                <textarea name="memo" class="form-control" id="memo" placeholder="早出の理由等" @input="submitInit()"></textarea>
              </div>
            </form>
            <div class="text-right">
              <input type="hidden" name="date" :value="date" form="form">
              <a :href="earlyTimeShowLink(authId(), date)" class="btn btn-gradient-light mr-2">Back</a>
              <button type="button" class="btn btn-gradient-info" form="form" @click="submit()">Submit</button>
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
  props: ['date', 'start_at', 'end_at'],
  mixins: [Data, Link],
  data(){
    return {
      'is_error': false,
    }
  },
  mounted(){

  },
  computed: {
    dateFormat(){
      var D = new Date(this.date)
      var y = D.getFullYear()
      var m = D.getMonth() + 1
      var d = D.getDate()

      return `${y}年${m}月${d}日`
    },
    workStampStatus(){
      return `${this.start_at}~${this.end_at}`
    },
  },
  methods: {
    submit(){
      this.submitInit()

      if(document.getElementById('start-at').value == ''){
        document.getElementById('start-at').classList.add('border-danger')
        this.is_error = true
      }

      if(document.getElementById('end-at').value == ''){
        document.getElementById('end-at').classList.add('border-danger')
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
      this.is_error = false
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

_<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">打刻</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <a :href="userShowLink(authId())">Home</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">打刻</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
          <!-- 今日のシフト登録済 -->
          <div class="card-body" v-if="shift">
            <h4 class="page-title">{{ today }}</h4>
            <div class="text-center my-3">{{ shiftItem }}</div>
            <!-- シフトが出勤 -->
            <div v-if="shift.item == 1">
              <div class="text-center display-3">{{ now }}</div>
              <div v-if="!start_exists || !end_exists">
                <div class="text-danger text-center font-weight-bold my-3" v-if="is_error">入力不備があります</div>
                <form action="/work_stamp/stamping" method="post" class="col-md-6 col-lg-8 mx-auto" ref="form">
                  <input type="hidden" name="_token" :value="csrfToken()">
                  <div class="form-group">
                    <label for="exception">項目</label>
                    <select name="exception" id="exception" class="form-control" v-model="exception" @change="submitInit">
                      <option v-for="(excepton_item, val) in excepton_items" :value="val">{{ excepton_item }}</option>
                    </select>
                  </div>
                  <div class="form-group" v-show="exception == 1">
                    <label for="stamp-at">出勤時間</label>
                    <input type="time" name="stamp_at" id="stamp-at" class="form-control" :disabled="exception != 1" @change="submitInit">
                  </div>
                  <div class="form-group">
                    <label for="memo">備考</label>
                    <textarea name="memo" id="memo" class="form-control" cols="30" rows="10" :placeholder="placeholder" @input="submitInit"></textarea>
                  </div>
                  <input type="hidden" name="is_start" value="true" v-if="!start_exists">
                  <button type="button" class="btn btn-gradient-primary w-100" style="height: 60px" @click="submitInit(), submit()" v-if="!start_exists">出勤</button>
                  <button type="button" class="btn btn-gradient-primary w-100" style="height: 60px" @click="submitInit(), submit()" v-else-if="!end_exists">退勤</button>
                  <button type="button" class="btn btn-secondary w-100" style="height: 60px" disabled v-else>退勤済</button>
                </form>
              </div>
              <div class="mt-5 text-center font-weight-bold" v-else>
                <p>お仕事お疲れさまでした。</p>
              </div>
            </div>
            <!-- シフトが出勤以外 -->
            <div class="text-center font-weight-bold mt-5" v-else>
              <div v-if="!pending_shift">
                シフト変更する場合は<a :href="shiftCreateLink(date)">こちら</a>
              </div>
              <div v-else-if="pending_shift.item != 1">
                シフト変更する場合は<a :href="shiftEditLink(pending_shift.id)">こちら</a>
              </div>
              <div v-else>
                承認されるまで少々お待ち下さい
              </div>
            </div>
          </div>
          <!-- 今日のシフト未登録 -->
          <div class="card-body" v-else>
            <h4 class="page-title">{{ today }}</h4>
            <div class="text-center font-weight-bold mt-5">本日のシフトが登録されていません</div>
            <div class="text-center font-weight-bold mt-5 mb-3">
              シフト登録は<a :href="attendanceShowLink(authId())">こちら</a>から
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
  props: ['shift', 'pending_shift', 'start_exists', 'end_exists'],
  mixins: [Data, Link],
  data(){
    return {
      'now': '',
      'D': '',
      'y': '',
      'm': '',
      'd': '',
      'date': '',
      'shift_start_at': '',
      'shift_end_at': '',
      'exception': 0,
      'excepton_items': '',
      'is_error': false,
    }
  },
  mounted(){
    this.D = new Date()
    this.y = this.D.getFullYear()
    this.m = this.D.getMonth() + 1
    this.d = this.D.getDate()

    this.updateTime()
    setInterval(this.updateTime, 1000)

    this.date = `${this.y}-${this.m}-${this.d}`

    if(this.shift){
      this.shift_start_at = new Date(this.date + ' ' + this.shift.start_at)
      this.shift_end_at = new Date(this.date + ' ' + this.shift.end_at)
    }

    if(!this.start_exists){
      this.excepton_items = { 0: '', 1: '遅刻' }
    }else if(this.D < this.shift_end_at){
      this.excepton_items = { 1: '早退' }
      this.exception = 1
    }else{
      this.excepton_items = { 0: '', 1: '早退' }
    }

  },
  computed: {
    today(){
      return `${this.y}年${this.m}月${this.d}日`
    },
    shiftItem(){
      if(this.shift.item == 1){
        return `稼働 ${this.sliceTime(this.shift.start_at)}~${this.sliceTime(this.shift.end_at)}`
      }else if(this.shift.item == 2){
        return '公休'
      }else if(this.shift.item == 3){
        return '有給'
      }
    },
    placeholder(){
      if(this.exception == 1){
        return (!this.start_exists) ? '遅刻理由を入力してください' : '早退の理由を入力してください'
      }else{
        return ''
      }
    }
  },
  methods: {
    updateTime(){
      var D = new Date()
      var h = this.timeChange(D.getHours().toString())
      var i = this.timeChange(D.getMinutes().toString())
      var s = this.timeChange(D.getSeconds().toString())

      this.now = `${h}:${i}:${s}`
    },
    submit(){
      this.is_error = false

      if(this.exception == 1){
        var stamp_at_element = document.getElementById('stamp-at')
        var stamp_at = stamp_at_element.value

        if(stamp_at == ''){
          stamp_at_element.classList.add('border-danger')
          this.is_error = true
        }else{
          var stamp_at = new Date(this.date + ' ' + stamp_at)

          if(stamp_at <= this.shift_start_at || this.shift_end_at <= stamp_at){
            stamp_at_element.classList.add('border-danger')
            this.is_error = true
          }
        }

        if(document.getElementById('memo').value == ''){
          document.getElementById('memo').classList.add('border-danger')
          this.is_error = true
        }
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
    },
  }

}
</script>

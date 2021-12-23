<template>
  <tr>
    <td>{{ sliceTime(earlytime.start_at) }}</td>
    <td>{{ sliceTime(earlytime.end_at) }}</td>
    <td>{{ createdAt }}</td>
    <td style="max-width: 200px">
      <div class="over-x scrollbar-none">{{ earlytime.memo }}</div>
    </td>
    <td :class="approveClass(earlytime.approve)" style="width: 100px" v-if="earlytime.approve == 0">
      <div v-if="user.id == authId()">
        <a :href="earlyTimeEditLink(earlytime.id)" class="btn btn-sm btn-gradient-success">編集</a>
        <form :action="earlyTimeAction(earlytime.id)" class="d-inline-block" method="post">
          <input type="hidden" name="_token" :value="csrfToken()">
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit" class="btn btn-sm btn-gradient-danger">削除</button>
        </form>
      </div>
      <form :action="earlyTimeApproveAction(earlytime.id)" method="post" v-else>
        <input type="hidden" name="_token" :value="csrfToken()">
        <input type="hidden" name="date" :value="date">
        <button type="submit" name="ok" class="btn btn-sm btn-gradient-info">承認</button>
        <button type="submit" class="btn btn-sm btn-gradient-danger">却下</button>
      </form>
    </td>
    <td :class="approveClass(earlytime.approve)" v-else>
      <div>{{ approve(earlytime.approve) }}</div>
      <form :action="earlyTimeApproveAction(earlytime.id)" method="post" class="mt-1" v-if="user.id != authId()">
        <input type="hidden" name="_token" :value="csrfToken()">
        <input type="hidden" name="date" :value="date">
        <button type="submit" name="ok" class="btn btn-sm btn-gradient-info" v-if="earlytime.approve == 2">承認</button>
        <button type="submit" class="btn btn-sm btn-gradient-danger" v-else>却下</button>
      </form>
    </td>
  </tr>
</template>

<script>
import Data from '../common/Data.vue'
import Link from '../common/Link.vue'

export default {
  props: ['user', 'date', 'earlytime'],
  mixins: [Data, Link],
  computed: {
    createdAt(){
      var D = new Date(this.earlytime.created_at)
      var y = D.getFullYear()
      var m = D.getMonth() + 1
      var d = D.getDate()
      var h = D.getHours()
      var i = D.getMinutes()
      var s = D.getSeconds()

      return `${y}/${m}/${d} ${h}:${i}:${s}`
    },
  }
}

</script>

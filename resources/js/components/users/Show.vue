<template>
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title font-weight-bold">{{ title }}</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">{{ title }}</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Account</h4>
            <table class="table">
              <tbody>
                <tr>
                  <td>Username</td>
                  <td>{{ user.username }}</td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>{{ user.email }}</td>
                </tr>
                <tr>
                  <td>role</td>
                  <td>{{ role(user.role) }}</td>
                </tr>
              </tbody>
            </table>
            <div class="text-right">
              <a :href="userEditLink" class="btn btn-gradient-success mr-2">Edit</a>
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
  props: ['title', 'user'],
  mixins: [Data, Link],
  data(){
    return {
      'userEditLink': '/user/' + this.user.id + '/edit'
    }
  }
}
</script>

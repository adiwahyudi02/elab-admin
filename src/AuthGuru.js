
import axios from 'axios'

export default {
    login (email, pass, cb) {
      cb = arguments[arguments.length - 1]
      if (localStorage.token) {
        if (cb) cb(true)
        this.onChange(true)
        return
      }
      pretendRequest(email, pass, (res) => {
        if (res.authenticated) {
          localStorage.setItem('auth', JSON.stringify({
            'data': res.data,
            'token': res.token
          }));
          // localStorage.token = res.token
          if (cb) cb(true)
          this.onChange(true)
        } else {
          if (cb) cb(false)
          this.onChange(false)
        }
      })
    },
  
    getToken () {
      let token = JSON.parse(localStorage.getItem('auth'));
      if (token != null) {
        return token.token 
      }else{
        return false
      }
    },
  
    logout (cb) {
      delete localStorage.auth
      if (cb) cb()
      this.onChange(false)
    },
  
    loggedIn () {      
      var auth = ''
      auth = JSON.parse(localStorage.getItem('auth'));
      if (auth == null) {
        return false
      }else if(auth.data.role == 'guru'){
        return !!localStorage.getItem('auth');
      }else{
        return false
      }
    },
  
    onChange () {}
  }
  
  function pretendRequest (email, pass, cb) {

    axios.post('/api/app-guru/login/login.php', {
        email: email,
        password: pass
    }).then(response => {
        if(response.data.status){
          console.log(response.data.jwt);
          cb({
              authenticated: true,
              token: response.data.jwt,
              data: response.data.data
          })
        }
        else{
            cb({ authenticated: false })
        }
    })

    // setTimeout(() => {
    //   if (email === 'joe@example.com' && pass === 'password1') {
    //     cb({
    //       authenticated: true,
    //       token: Math.random().toString(36).substring(7)
    //     })
    //   } else {
    //     cb({ authenticated: false })
    //   }
    // }, 0)
  }
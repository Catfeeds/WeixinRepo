//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    activity: ["../../images/activity_n@2x.png", "../../images/activity_s@2x.png"],
    fengti: ["../../images/Home_n@2x.png", "../../images/Home_s@2x.png"],
    garden: ["../../images/garden_n@2x.png", "../../images/garden_s@2x.png"],
    mine: ["../../images/my_n@2x.png", "../../images/my_s@2x.png"],
    selected:[1,0,0,0]
  },
  onLoad: function () {
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getUserInfo: function(e) {
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  select: function(num){
  }
})
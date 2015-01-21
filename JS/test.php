<!DOCTYPE html>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
//添加鼠标事件
$(document.documentElement).mousedown(function(e){
  //只负责鼠标右键
  if(e.button!=2)return;
  //声明变量
  var track=[],dist=0,events,canvas,g;
  //记录当前鼠标位置
  track.push([e.clientX,e.clientY]);
  //创建一个用于绘制轨迹的CANVAS
  g=(canvas=$("<canvas/>").css({
    position:"fixed",zIndex:1000,top:0,left:0
  }).appendTo(document.body).attr({
    width:this.clientWidth,height:this.clientHeight
  })).get(0).getContext("2d");
  //初始化轨迹绘制
  g.lineWidth=3,g.moveTo(track[0][0],track[0][1]);
  //设置Capture，兼容IE和FF
  if(this.setCapture)this.setCapture();
  //绑定过程事件
  $(this).on(events={
    mousemove:function(e){
      //绘制轨迹
      g.lineTo(e.clientX,e.clientY),g.stroke();
      //记录轨迹
      track.push([e.clientX,e.clientY]);
    },mouseup:function(e){
      //只负责鼠标右键
      if(e.button!=2)return;
      //释放Capture，兼容IE和FF
      if(this.releaseCapture)this.releaseCapture();
      //移除CANVAS
      canvas.remove();
      //在消息队列中添加一个动作
      setTimeout(function(){
        //注销事件
        $(this).off(events);
      }.bind(this),0);
      //如果轨迹太短则不做其它事情
      if(track.length<2)return;
      //初始化变量
      var i=0,o,a,b,c,d,k,st,ed;
      //遍历轨迹，找到轨迹所在的最小矩形区域
      while(o=track[i++])
        o[0]>=a||(a=o[0]),o[0]<=b||(b=o[0]),
        o[1]>=c||(c=o[1]),o[1]<=d||(d=o[1]);
      //计算轨迹矩形宽度和高度
      k=(d-c)/(b-a);
      //获取轨迹的开始位置和结束位置
      a=track[0],b=track[track.length-1];
      //计算鼠标开始位置到结束位置的水平和垂直距离
      c=a[0]-b[0],d=a[1]-b[1];
      //斜率大于1（垂直）
      if(k>1){
        //根据鼠标开始和结束位置判断方向
        if(d<-100)console.log("↓");
        if(d>100)console.log("↑");
      };
      //斜率小于1（水平）
      if(k<1){
        //根据鼠标开始和结束位置判断方向
        if(c<-100)console.log("→");
        if(c>100)console.log("←");
      };
      //计算鼠标从开始到结束的垂直距离
      dist=Math.sqrt(c*c+d*d);
    },contextmenu:function(){
      //如果鼠标移动的距离不小于100就阻止右键菜单
      return dist<100;
    }
  });
});
</script>

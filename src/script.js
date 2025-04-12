const crsr=document.querySelector("#cursor");
const blr=document.querySelector("#cursor-blur")

document.addEventListener("mousemove",(dets)=>{
  crsr.style.left=dets.x-10 + "px";
  crsr.style.top=dets.y-10 + "px";
  blr.style.left=dets.x -250 + "px";
  blr.style.top=dets.y -250 + "px";

})

function c(ele){
  ele.style.color="greenyellow";
}

gsap.to("#nav",{
  height:"110px",
  backgroundColor:"black",
  duration:0.5,
  scrollTrigger:{
    trigger:"#nav",
    scroller:"body",
    start:"top -10%",
    end:"top -11%",
    scrub:1,
  },
});

gsap.to("#main",{
  backgroundColor:"black",
  // durtion:0.5,
  scrollTrigger:{
    trigger:"#main",
    scroller:"body",
    start:"top -25%",
    end: "top -65%",
    scrub:2,
  },
});
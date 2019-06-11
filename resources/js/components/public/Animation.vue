<template>
  <div class="lds-hourglass">
  </div>
</template>
<script>
export default {
  created() {
      Event.$on('completedLoad',()=>{
        const loader = document.querySelector(".lds-hourglass");
        loader.className += " hidden"; // class "loader hidden"
      });
  },
};
</script>
<style>
    .lds-hourglass {
        /* display: inline-block; */
        /* position: relative; */
        /* width: 64px;
        height: 64px; */
        position: fixed;
        z-index: 99;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        }
    .lds-hourglass:after {
        content: " ";
        display: block;
        border-radius: 50%;
        width: 0;
        height: 0;
        margin: 6px;
        box-sizing: border-box;
        border: 26px solid #fff;
        border-color: rgb(10, 94, 219) transparent rgb(10, 94, 219) transparent;
        animation: lds-hourglass 1.2s infinite;
    }

    .lds-hourglass.hidden {
        animation: fadeOut 1s;
        animation-fill-mode: forwards;
    }
    @keyframes lds-hourglass {
        0% {
            transform: rotate(0);
            animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
        }
        50% {
            transform: rotate(900deg);
            animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        }
        100% {
            transform: rotate(1800deg);
            }
        }
    @keyframes fadeOut {
        100% {
            opacity: 0;
            visibility: hidden;
        }
}

</style>



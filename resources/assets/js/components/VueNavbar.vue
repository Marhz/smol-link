<script>
	// import { bCollapse, VBCollapse } from 'bootstrap-vue';
	export default {
		// components: { bCollapse },
		data() {
			return {
				show: false,
				showing: false
			}
		},
		methods: {
			toggle() {
				this.show = ! this.show;
				this.showing = ! this.showing;
				setTimeout(() => this.showing = ! this.showing, 1000);
			}
		},
		computed: {
			btnClass() {
				return (! this.show) ? 'collapsed' : ''
			},
			navbarClass() {
				// return (this.show) ? 'show' : ''
				if (this.show && ! this.showing) {
					return 'show'
				} else if (this.showing) {
					return 'collapsing h'
				} else {
					return '';
				}
			},
			loaded() {
				return "show";
			}
		}
	}
</script>
 <style>
 	.h {
 		/*height: 80px;*/
 		/*display: block;*/
 	}
	.slide-fade-enter-active {
	  /*transition: all 1s ease;*/
	  max-height: 100px;
	  /*transform-origin: top;*/

	}
	.slide-fade-leave-active {
/*	  transition: all 1s;
	  transform: scaleY(1);
	  transform-origin: top;
	  background: red;
*/
	max-height: 0px;
	}
	.slide-fade-enter
	/* .slide-fade-leave-active below version 2.1.8 */ {
	  /*transform: scaleY(0);*/
/*	  transform-origin: top;
	  background: red;
*/	  /*opacity: 0;*/

	  transition: all 1s ease;
	}
	.slide-fade-leave-to {
/*		transform:scaleY(0);
		transform-origin: top;
		background: red;

*/
	  transition: all 1s ease;

}
</style>


<style>
	.slide-fade-enter-active {
	  /*transition: all 1s ease;*/
	  animation: testy 1s;
	  /*background: red;*/
	  transform-origin: top;

	}
	.slide-fade-leave-active {
	  /*transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);*/
	  /*transition: all .5s ease;*/

	}
	.slide-fade-enter, .slide-fade-enter *
	/* .slide-fade-leave-active below version 2.1.8 */ {
	  /*transform: translateY(-10px);*/
	  /*background: red;*/
	  /*animation: testy .5s;*/
	  /*height: 0;*/
	  /*opacity: 0;*/
	}
	.slide-fade-leave-to, .slide-fade-leave-to * {
		/*background: red;*/
		animation: test 1s;
		transform-origin: top;
	}
	@keyframes test {
		0% {
			max-height: 80px;
/*			transform: scaleY(1);
*/		}

		100% {
			max-height: 0px;
/*			transform: scaleY(0);
*/		}
	}
	@keyframes testy {
		0% {
			max-height: 0px;
/*			transform: scaleY(0);
*/		}

		100% {
			max-height: 80px;
/*			transform: scaleY(1);
*/
		}
	}
</style>



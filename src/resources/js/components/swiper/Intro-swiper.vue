
<template>
    <div class="thumb-example">
        <!-- swiper1 -->

        <swiper
            class="swiper gallery-top"
            :options="swiperOptionTop"
            ref="swiperTop"
        >
            <swiper-slide
                class="flex-center"
                v-for="(item, index) in filterData"
                :key="index"
            >
                <img
                    class="max-img"
                    :src="'album/' + item.album_id + '/' + item.path"
                />
            </swiper-slide>
            <!-- <swiper-slide> slide-1</swiper-slide>
            <swiper-slide> slide-2</swiper-slide>
            <swiper-slide> slide-3</swiper-slide>
            <swiper-slide> slide-4</swiper-slide>
            <swiper-slide> slide-5</swiper-slide> -->
            <div
                class="swiper-button-next swiper-button-white"
                slot="button-next"
            ></div>
            <div
                class="swiper-button-prev swiper-button-white"
                slot="button-prev"
            ></div>
        </swiper>
        <!-- swiper2 Thumbs -->
        <swiper
            class="swiper gallery-thumbs"
            :options="swiperOptionThumbs"
            ref="swiperThumbs"
        >
            <swiper-slide v-for="(item, index) in filterData" :key="index">
                <img :src="'album/' + item.album_id + '/small/' + item.path" />
            </swiper-slide>
            <!-- <swiper-slide> <span @click="toSlide(1)">1</span></swiper-slide>
            <swiper-slide> <span @click="toSlide(2)">2</span></swiper-slide>
            <swiper-slide><span @click="toSlide(3)">3</span></swiper-slide>
            <swiper-slide> <span @click="toSlide(4)">4</span></swiper-slide>
            <swiper-slide> <span @click="toSlide(5)">5</span></swiper-slide> -->
        </swiper>
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
// import "swiper/dist/css/swiper.css";
import "swiper/swiper-bundle.css";
// import "swiper/css/swiper.css";

export default {
    props: ["swiperInfo", "photoId"],
    components: {
        Swiper,
        SwiperSlide,
    },
    data() {
        return {
            swiperOptionTop: {
                // notNextTick: true,
                loop: true,
                loopedSlides: 5, // looped slides should be the same
                spaceBetween: 10,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                grabCursor: true,
                // height: window.innerHeight,
                on: {
                    //監聽滑動切換事件，返回swiper物件
                    slideChange: () => {
                        let swiper = this.$refs.swiperTop.$swiper;
                        if (swiper !== undefined) {
                            this.toSlideThumbs(swiper.activeIndex);
                        }
                    },
                },
            },
            swiperOptionThumbs: {
                // notNextTick: true,
                loop: true,
                loopedSlides: 5, // looped slides should be the same
                spaceBetween: 10,
                centeredSlides: true,
                slidesPerView: "auto",
                touchRatio: 0.2,
                slideToClickedSlide: true,
                grabCursor: true,
                on: {
                    //監聽滑動切換事件，返回swiper物件
                    slideChange: () => {
                        let swiper = this.$refs.swiperThumbs.$swiper;
                        if (swiper !== undefined) {
                            this.toSlideTop(swiper.activeIndex);
                        }
                    },
                },
            },
        };
    },
    mounted() {
        // this.$nextTick(() => {
        //     const swiperTop = this.$refs.mySwiper.swiper;
        //     const swiperThumbs = this.$refs.swiperThumbs.swiper;
        //     swiperTop.controller.control = swiperThumbs;
        //     swiperThumbs.controller.control = swiperTop;
        // });
    },
    computed: {
        filterData: function () {
            let head = [];
            let sliceData = [];
            for (var i = 0; i < this.swiperInfo.length; i++) {
                if (this.swiperInfo[i].photo_id === this.photoId) {
                    sliceData = this.swiperInfo.slice(i);
                    break;
                }
                head = [...head, this.swiperInfo[i]];
            }
            const newData = sliceData.concat(head);

            this.swiperOptionTop.loopedSlides = newData.length;
            this.swiperOptionThumbs.loopedSlides = newData.length;
            return newData;
        },
        swiperTop: {
            get: function () {
                return this.$refs.swiperTop.$swiper;
            },
        },
        swiperThumbs: {
            get: function () {
                return this.$refs.swiperThumbs.$swiper;
            },
        },
    },
    filters: {
        // small: function (url) {
        //     return url.replace(".jpg", "_small.jpg");
        // },
        // urlSplitAlbum: function (url) {
        //     return url.replace("album/", "");
        // },
    },
    watch: {
        // filterData: function (n, o) {
        //     console.log(0);
        //     this.swiperOptionTop.loopedSlides = n[0].photos.length;
        //     this.swiperOptionThumbs.loopedSlides = n[0].photos.length;
        // },
        // swiperThumbs: function (n, o) {
        //     console.log(n);
        //     // this.$nextTick(() => {
        //     //     this.swiperTop.controller.control = n;
        //     // });
        // },
    },
    methods: {
        toSlideTop(i) {
            // await this.$nextTick(() => {
            //     const swiperTop = this.$refs.swiperTop.$swiper;
            //     const swiperThumbs = this.$refs.swiperThumbs.$swiper;
            //     swiperTop.controller.control = swiperThumbs;
            //     swiperThumbs.controller.control = swiperTop;
            // });
            // console.log("click", i);
            this.swiperTop.slideTo(i);
        },
        toSlideThumbs(i) {
            // await this.$nextTick(() => {
            //     const swiperTop = this.$refs.swiperTop.$swiper;
            //     const swiperThumbs = this.$refs.swiperThumbs.$swiper;
            //     swiperTop.controller.control = swiperThumbs;
            //     swiperThumbs.controller.control = swiperTop;
            // });
            // console.log("click", i);
            // console.log(this.swiperOptionTop.loopedSlides);
            this.swiperThumbs.slideTo(i);
        },
    },
};
</script>

<style lang="scss" scoped>
.thumb-example {
    height: 80vh;
    background-color: black;
}

.swiper {
    .swiper-slide {
        background-size: cover;
        background-position: center;
        &.flex-center {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centering y-axis */
            align-items: center; /* Centering x-axis */
        }
        .max-img {
            max-height: 80%;
        }
        // &.slide-1 {
        //     background-image: url("/images/example/1.jpg");
        // }
        // &.slide-2 {
        //     background-image: url("/images/example/2.jpg");
        // }
        // &.slide-3 {
        //     background-image: url("/images/example/4.jpg");
        // }
        // &.slide-4 {
        //     background-image: url("/images/example/5.jpg");
        // }
        // &.slide-5 {
        //     background-image: url("/images/example/6.jpg");
        // }
    }

    &.gallery-top {
        height: 80%;
        width: 100%;
    }
    &.gallery-thumbs {
        height: 20%;
        box-sizing: border-box;
        padding: 0;
    }
    &.gallery-thumbs .swiper-slide {
        width: 25%;
        height: 100%;
        opacity: 0.4;
    }
    &.gallery-thumbs .swiper-slide-active {
        opacity: 1;
    }
}
</style>
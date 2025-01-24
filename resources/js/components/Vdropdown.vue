<template>
    <div class="relative">
        <button type="button" class="z-10 relative flex items-center focus:outline-none select-none " :class="'float-'+placement "
                @click="open = !open">
              <span class="px-2 py-2 border rounded inline-flex items-center text-sm">
                <span class="mr-2">{{ dropdownTitle }}</span>

                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path
                      d="M4.516 7.548c.436-.446 1.043-.481 1.576 0L10 11.295l3.908-3.747c.533-.481 1.141-.446 1.574 0 .436.445.408 1.197 0 1.615-.406.418-4.695 4.502-4.695 4.502a1.095 1.095 0 0 1-1.576 0S4.924 9.581 4.516 9.163c-.409-.418-.436-1.17 0-1.615z"/>
                </svg>
              </span>
        </button>

        <!-- to close when clicked on space around it in desktop-->
        <button class="fixed inset-0 h-full w-full cursor-default focus:outline-none" v-if="open" @click="open = false"
                tabindex="-1"></button>
        <!--dropdown content: desktop-->
        <transition enter-active-class="transition-all duration-200 ease-out"
                    leave-active-class="transition-all duration-750 ease-in" enter-class="opacity-0 scale-75"
                    enter-to-class="opacity-100 scale-100" leave-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-75">
            <div class="hidden md:block absolute z-10 shadow-lg border w-full rounded py-1 px-2 text-sm mt-2 bg-white"
                 :class="placement === 'right' ? 'right-0' : 'left-0'" v-if="open">
                <slot name="content">
                    <span
                        class="flex w-full justify-between items-center rounded px-2 py-1 my-1 hover:bg-indigo-600 hover:text-white"
                        @click="chooseOption(index)" v-for="(option,index) in options" :key="index">{{ index }}</span>
                </slot>
            </div>
        </transition>

        <!--dropdown content: mobile-->
        <transition enter-active-class="transition-all duration-200 ease-out"
                    leave-active-class="transition-all duration-750 ease-in" enter-class="opacity-0 scale-75"
                    enter-to-class="opacity-100 scale-100" leave-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-75">
            <div
                class="md:hidden fixed inset-x-0  z-10 bottom-0 bg-white w-full z-20 px-2 py-2 shadow-2xl leading-loose"
                v-if="open">
                <slot name="content">
                                        <span
                                            class="flex w-full justify-between items-center rounded px-2 py-1 my-1 hover:bg-indigo-600 hover:text-white"
                                            @click="chooseOption(index)" v-for="(option,index) in options" :key="index">{{ index }}</span>
                </slot>
            </div>
        </transition>
        <!-- to close when clicked on space around it in mobile-->
        <div class="md:hidden fixed w-full h-full inset-0 bg-gray-900 opacity-50 z-10" @click="open = false"
             v-if="open"></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            open: false,
        };
    },
    props: {
        placement: {
            type: String,
            default: "right",
            validator: (value) => ["right", "left"].indexOf(value) !== -1,
        },
        options: {
            type: Array,
            require: true
        },
        dropdownTitle: {
            type: String,
            require: true
        },
        chooseOption: {
            type: Function,
            require: true,
        },
    },
    mounted() {
        const onEscape = (e) => {
            if (e.key === "Esc" || e.key === "Escape") {
                this.open = false;
            }
        };

    },
};
</script>

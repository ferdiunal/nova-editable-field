<template>
    <PopoverRoot :open="toggle" :modal="false">
        <PopoverTrigger
            class="nr-rounded-full nr-w-[35px] nr-h-[35px] nr-inline-flex nr-items-center nr-justify-center nr-outline-none"
            aria-label="Open popover"
            @click="trigger"
            v-tooltip="{
        content: 'Edit',
        delay: {
          show: 1,
          hide: 0,
        },
      }"
        >
            <PencilSquareIcon class="w-5 h-5"/>
        </PopoverTrigger>

        <PopoverPortal>
            <PopoverContent
                :side="side"
                :side-offset="5"
                @interactOutside="interactOutside"
                class="nr-p-4 dark:!nr-bg-gray-900 nr-bg-white nr-shadow-lg nr-border nr-rounded-lg nr-will-change-[transform,opacity] data-[state=open]:data-[side=top]:animate-slideDownAndFade data-[state=open]:data-[side=right]:animate-slideLeftAndFade data-[state=open]:data-[side=bottom]:animate-slideUpAndFade data-[state=open]:data-[side=left]:animate-slideRightAndFade"
                :style="{
          width,
        }"
            >
                <div class="nr-grid nr-gap-y-4">
                    <slot></slot>
                    <div class="nr-grid nr-grid-cols-2 nr-gap-x-4">
                        <DefaultButton size="sm" @click="$emit('confirm')">
                            Confirm
                        </DefaultButton>
                        <BasicButton size="sm" @click.stop="toggle = false"
                        >Cancel
                        </BasicButton
                        >
                    </div>
                </div>
                <PopoverArrow
                    data-popover-arrow
                    class="nr-fill-white dark:nr-fill-gray-800"
                />
            </PopoverContent>
        </PopoverPortal>
    </PopoverRoot>
</template>

<script>
import {PopoverArrow, PopoverClose, PopoverContent, PopoverPortal, PopoverRoot, PopoverTrigger,} from "radix-vue";
import {PencilSquareIcon} from '@heroicons/vue/24/outline';

export default {
    props: {
        side: {
            type: String,
            default: "top",
        },
        width: {
            type: String,
            default: "270px",
        },
        modelValue: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["confirm"],
    components: {
        PopoverArrow,
        PopoverClose,
        PopoverContent,
        PopoverPortal,
        PopoverRoot,
        PopoverTrigger,
        PencilSquareIcon
    },
    data() {
        return {
            toggle: false,
        };
    },
    methods: {
        trigger() {
            this.toggle = !this.toggle;
        },
        interactOutside(e) {
            if (!e.srcElement.closest("[data-popper-placement]")) {
                this.toggle = false;
            }
        },
    },
};
</script>

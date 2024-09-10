<template>
    <div @click.prevent.stop="false">
        <div v-if="loading" class="flex items-center justify-center h-64">
            <Loader class="text-gray-300" />
        </div>
        <div
            v-else-if="!loading"
            class="nr-flex nr-flex-row nr-gap-x-4 nr-items-center"
            @click.stop
        >
            <component
                :is="viewField.component"
                :resourceName="resourceName"
                :field="viewField"
            />
            <popover :side="side" v-model="isPopoverOpen" @confirm="onConfirm" :width="field.popoverWidth">
                <component
                        :is="formFieldComponent.component"
                        :resourceName="resourceName"
                        class="nova-editable-field"
                        :field="field"
                    />
            </popover>
        </div>
    </div>
</template>

<script>
export default {
    props: ["resourceName", "resource", "field"],
    data() {
        return {
            loading: false,
            isEdit: false,
            isPopoverOpen: false,
        };
    },
    computed: {
        fieldValue() {
            return this.field.displayedAs || this.field.value;
        },
        viewField() {
            return {
                ...this.field,
                // ...field,
                component: `index-${this.field.original_field}`,
            };
        },
        formFieldComponent() {
            return {
                ...this.field,
                component: `form-${this.field.original_field}`,
            }
        },
        resources() {
            try {
                return this.$parent.$parent.resources;
            } catch (error) {
                return [];
            }
        },
        currentResourceIndex() {
            return this.resources.findIndex(
                (resource) => resource === this.resource
            );
        },
        side() {
            return this.currentResourceIndex > this.resources.length - 3
                ? "top"
                : "bottom";
        },
    },
    methods: {
        onConfirm() {
            this.loading = true;
            const formData = new FormData();
            this.formFieldComponent.fill(formData);
            
            Nova.request()
                .post(
                    `${window.NovaEditableField.baseUrl}/confirm/${this.resourceName}/${this.resource.id.value}/${this.field.attribute}`,
                    formData
                )
                .then(() => {
                    this.loading = false;
                    Nova.$emit("refresh-resources")
                });
        },
    },
};
</script>

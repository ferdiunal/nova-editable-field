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
      <popover
        :side="side"
        v-model="isPopoverOpen"
        @confirm="onConfirm"
        :width="field.popoverWidth"
      >
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
    computed:   {
        fieldValue() {
            return this.field.displayedAs || this.field.value;
        },
        viewField() {
            return {
                ...this.field,
                component: `index-${this.field.original_field}`,
            };
        },
        formFieldComponent() {
            return {
                ...this.field,
                component: `form-${this.field.original_field}`,
            };
        },
        resources() {
            try {
                return this.$parent.$parent.resources;
            } catch (error) {
                return [];
            }
        },

        currentResourceIndex() {
            const resources = this.resources;

            let list = [];
            if (Array.isArray(resources)) {
                list = resources;
            } else if (resources && typeof resources === "object") {
                list = Object.values(resources);
            } else {
                list = [];
            }

            const idxByIdentity = list.findIndex((r) => r === this.resource);
            if (idxByIdentity !== -1) return idxByIdentity;

            const resourceId =
                (this.resource && this.resource.id && this.resource.id.value) ??
                (this.resource && this.resource.value) ??
                null;

            if (resourceId !== null) {
                const idxById = list.findIndex((r) => {
                    const rId = (r && r.id && r.id.value) ?? (r && r.value) ?? null;
                    return rId === resourceId;
                });
                if (idxById !== -1) return idxById;
            }

            const idxByAttr = list.findIndex((r) =>
                r && this.resource
                    ? Object.keys(this.resource).every((k) =>
                        r.hasOwnProperty(k) ? r[k] === this.resource[k] : true
                    )
                    : false
            );
            if (idxByAttr !== -1) return idxByAttr;

            return 0;
        },

        side() {
            const idx = Number.isFinite(this.currentResourceIndex)
                ? this.currentResourceIndex
                : 0;
            const len = Array.isArray(this.resources)
                ? this.resources.length
                : this.resources && typeof this.resources === "object"
                    ? Object.keys(this.resources).length
                    : 0;

            return idx > len - 3 ? "top" : "bottom";
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
          Nova.$emit("refresh-resources");
        });
    },
  },
};
</script>

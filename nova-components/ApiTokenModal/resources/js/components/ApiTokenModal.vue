<template>
  <Modal role="dialog" :show="true" data-testid="show-api-token-modal">
    <form class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      <ModalHeader>Token Generated</ModalHeader>

      <ModalContent>
        <div class="action">
          <div class="flex flex-col border-b border-40">
            <div class="flex items-center py-6 px-8">
              <input
                ref="token"
                id="name"
                v-model="token"
                type="text"
                placeholder="Name"
                class="w-full form-control form-input form-input-bordered"
                readonly
              />
              <BasicButton
                type="button"
                @click="copyToken"
              >
                {{ __("Copy") }}
              </BasicButton>
            </div>
          </div>
        </div>
        <div class="p-4 rounded bg-yellow-50">
          Make sure to copy your new personal access token now. You won't be able to see it again!
        </div>
      </ModalContent>

      <ModalFooter>
        <DefaultButton @click="show = false">Close</DefaultButton>
      </ModalFooter>
    </form>
  </Modal>
</template>

<script>
export default {
  /**
   * Mount the component.
   */
  mounted() {
    document.querySelectorAll(".modal input")[0].focus()
    console.log(this.$props.token)
  },
  data: () => {
    return {
      show: true
    }
  },
  props: {
    token: {type: String}
  },
  methods: {
    /**
     * Stop propogation of input events unless it's for an escape or enter keypress
     */
    handleKeydown(e) {
      if (["Escape", "Enter"].indexOf(e.key) !== -1) {
        return
      }
      e.stopPropagation()
    },
    /**
     * Execute the selected action.
     */
    handleConfirm() {
      this.$emit("confirm")
    },
    copyToken() {
      this.$refs.token.select()
      document.execCommand("copy")
    },
  },
}
</script>
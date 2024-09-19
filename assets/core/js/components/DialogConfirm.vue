<template>
  <div ref="modal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-confirm">
      <div class="modal-content">
        <div class="modal-header">
          <h2 v-if="icon" class="modal-title mx-auto mb-1">{{ trustedIcon }}</h2>
          <h5 v-else class="modal-title">{{ title }}</h5>
        </div>
        <div class="modal-body text-muted fs-6 py-3">
          <p>{{ message }}</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-secondary ms-2" type="button" @click="_cancel">
            {{ cancelButton }}
          </button>
          <button class="btn btn-danger me-2" type="button" @click="_confirm">
            {{ okButton }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DialogConfirm",
  data() {
    return {
      modal: null,
      title: undefined,
      message: undefined, // Main text content
      okButton: "OK",
      cancelButton: "Annuler",
      icon: undefined,
      // Private variables
      resolvePromise: undefined,
      rejectPromise: undefined,
    };
  },
  mounted() {
    this.modal = new $boosted.Modal(this.$refs.modal, {
      backdrop: "static",
    });
  },
  computed: {
    trustedIcon() {
      return this.icon;
    },
  },
  methods: {
    /**
     *
     * @param {Object} opts
     * @returns {Promise<unknown>}
     */
    show(
      opts = {
        title: undefined,
        message: undefined, // Main text content
        okButton: undefined, // Text for confirm button; leave it empty because we don't know what we're using it for
        cancelButton: undefined, // text for cancel button
        icon: undefined, // icon replace title
      }
    ) {
      this.title = opts.title ? opts.title : this.title;
      this.message = opts.message ? opts.message : this.message;
      this.okButton = opts.okButton ? opts.okButton : this.okButton;
      this.cancelButton = opts.cancelButton
        ? opts.cancelButton
        : this.cancelButton;
      this.icon = opts.icon ? opts.icon : this.icon;

      this.modal.show();
      // Return promise so the caller can get results
      return new Promise((resolve, reject) => {
        this.resolvePromise = resolve;
        this.rejectPromise = reject;
      });
    },
    _confirm() {
      this.modal.hide();
      this.resolvePromise(true);
    },
    _cancel() {
      this.modal.hide();
      this.resolvePromise(false);
    },
  },
};
</script>

<style lang="scss" scoped>
.modal-confirm {
  button.btn-secondary {
    background: var(--bs-light);
    border-color: var(--bs-light);
  }

  .modal-footer {
    border: none;
    text-align: center;
    font-size: 13px;
    padding: 10px 15px 25px;
  }

  .modal-content {
    padding: 20px;
    border: none;
    text-align: center;
    font-size: 14px;
  }

  button.btn {
    transition: all 0.4s;
    line-height: normal;
    min-width: 120px;
    min-height: 40px;
    margin: 0 5px;
  }
}
</style>

// src/Resources/public/src/helper/extend.js
function extend() {
  var obj, name, copy, target = arguments[0] || {}, i = 1, length = arguments.length;
  for (; i < length; i++) {
    if ((obj = arguments[i]) !== null) {
      for (name in obj) {
        copy = obj[name];
        if (target === copy) {
          continue;
        } else if (copy !== void 0) {
          target[name] = copy;
        }
      }
    }
  }
  return target;
}

// src/Resources/public/src/NewsPopup.js
var NewsPopup = class {
  constructor(popup, options) {
    this.options = extend({
      closeButton: null,
      delay: 0,
      storage: {
        mode: "local",
        key: "cnp",
        data: null
      },
      onOpen: () => {
      },
      onClose: () => {
      }
    }, options || {});
    this.popup = popup;
    this.storage = this.#getStorage();
    this.#registerEvents();
    if (!this.storage?.hasOwnProperty(this.currentId)) {
      setTimeout(() => {
        this.open();
      }, this.options.delay);
    }
  }
  #registerEvents() {
    if (!!this.options.closeButton) {
      this.options.closeButton.addEventListener("click", () => {
        this.close(true);
      });
    }
    let item = document.querySelector("[data-np-id]");
    if (!!item) {
      this.currentId = item?.dataset?.npId;
      item.querySelectorAll("a").forEach((el) => {
        el.addEventListener("click", () => {
          this.#save();
        });
      });
    }
  }
  #save() {
    if (!this.currentId)
      return;
    if (!this.storage) {
      this.storage = {};
    }
    this.storage[this.currentId] = {
      id: this.currentId,
      date: new Date()
    };
    localStorage.setItem(this.options.storage.key, JSON.stringify(this.storage));
  }
  #getStorage() {
    switch (this.options.storage.mode) {
      case "local":
        return JSON.parse(localStorage.getItem(this.options.storage.key));
      case "database":
        return this.options.storage.data;
    }
  }
  open() {
    this.popup.classList.add("open");
    this.options.onOpen.call(this);
  }
  close(persist) {
    if (!!persist) {
      this.#save();
    }
    this.popup.classList.remove("open");
    this.options.onClose.call(this);
  }
};
module.exports = NewsPopup;

import {extend} from "./helper/extend";

class NewsPopup {

    constructor(popup, options) {
        this.options = extend({
            closeButton: null,
            delay: 0,
            storage: {
                mode: 'local',              // local, database
                key: 'cnp',
                data: null                  // Must be passed if the mode is set to database
            },
            onOpen: () => {},
            onClose: () => {}
        }, options || {})

        this.popup = popup
        this.storage = this.#getStorage()

        this.#registerEvents()

        if(!this.storage?.hasOwnProperty(this.currentId)) {
            setTimeout(()=>{
                this.open()
            }, this.options.delay)
        }
    }

    #registerEvents(){
        // Close button event
        if(!!this.options.closeButton){
            this.options.closeButton.addEventListener('click', () => {
                this.close(true)
            })
        }

        // Click link event
        let item = document.querySelector('[data-np-id]');

        if(!!item){
            this.currentId = item?.dataset?.npId;

            item.querySelectorAll('a').forEach((el) => {
                el.addEventListener('click', () => { this.#save() });
            })
        }
    }

    #save(){
        if(!this.currentId) return;

        if(!this.storage){
            this.storage = {}
        }

        this.storage[ this.currentId ] = {
            id: this.currentId,
            date: new Date()
        };

        localStorage.setItem(this.options.storage.key, JSON.stringify(this.storage));
    }

    #getStorage(){
        switch (this.options.storage.mode){
            case 'local':
                return JSON.parse(localStorage.getItem(this.options.storage.key))
            case 'database':
                return this.options.storage.data
        }
    }

    open(){
        this.popup.classList.add('open')
        this.options.onOpen.call(this)
    }

    close(persist){

        if(!!persist){
            this.#save()
        }

        this.popup.classList.remove('open')
        this.options.onClose.call(this)
    }
}

module.exports = NewsPopup
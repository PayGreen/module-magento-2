/**
 * 2014 - 2021 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License X11
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/mit-license.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2021 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   1.2.3
 *
 */
let start = new Date().getTime();
let countElement = 0;

let tree = {

    install() {
        this.countElement();
        this.sendRequest();
    },

    countElement() {
        const imgs = document.querySelectorAll('img');
        const scripts = document.querySelectorAll('script');
        
        countElement = imgs.length + scripts.length;
    },

    sendRequest() {
        let url = window['paygreen_tree_computing_url'];

        if (!url) {
            console.log("PayGreen fingerprint url not found.");
        } else {
            this.makeXmlHttpRequest('POST', url, this.buildData());
        }
    },

    makeXmlHttpRequest(method, url, data, async = true) {
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, async);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send(new URLSearchParams(data));

        if (xhr.readyState === 4) { // State 4 means the request is done
            if (xhr.status !== 200) {
                console.log("PayGreen fingerprint computing error.");
            }
        }
    },

    buildData() {
        let client = new ClientJS();
        let device;

        if (client.isMobile()) {
            device = 'mobile';
        } else {
            device = 'desktop';
        }

        let end = new Date().getTime();

        return {
            client: client.getFingerprint(),
            browser: client.getBrowser(),
            device: device,
            startAt: start,
            useTime: (end - start),
            nbImage: countElement
        };
    }

};

window.addEventListener('load', tree.install.bind(tree));

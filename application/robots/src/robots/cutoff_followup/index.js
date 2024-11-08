const CONFIG = require('../../config/config');
const generalHelper = require("../../helpers/general");
const puppeteer = require('puppeteer');

const initBrowser = async () => {
    const browser = await puppeteer.launch({
        headless: true,
        slowMo: 100,
        args: [`--window-size=1920,1080`],
        defaultViewport: {
            width: 1920,
            height: 1080
        }
    });
    return browser;
};

const login = async page => {
    await page.goto(CONFIG.integrations.rpa.jarvis_url + "/login");
    await page.type("#prueba", CONFIG.integrations.api.user);
    await page.type("#password", CONFIG.integrations.api.password);
    await page.click(".button-singin");
    try {
        await page.waitForSelector("#user-info", { timeout: 5000 });
    } catch (e) {
        if (e) {
            generalHelper.showDetails("Error attempting to login");
        }
    }
    await page.goto(CONFIG.integrations.rpa.jarvis_url + "/gtr/administration/configuracion-cortes-notificaciones");
};

const setCookie = async (browser) => {
    const cookies = [
        { name: 'jj_s', value: CONFIG.integrations.rpa.jwt_jarvis, domain: CONFIG.integrations.rpa.jarvis_domain },
        { name: 'jj_sr', value: CONFIG.integrations.rpa.jwt_recovery_jarvis, domain: CONFIG.integrations.rpa.jarvis_domain },
        { name: 'jj_a', value: '100', domain: CONFIG.integrations.rpa.jarvis_domain }
    ];

    const page = await browser.newPage();
    await page.setCookie(...cookies);
    await page.goto(CONFIG.integrations.rpa.jarvis_url + "/gtr/administration/configuracion-cortes-notificaciones");
    return page
}


const openAllPagesWithData = async (page, browser) => {
    try {
        await page.waitForSelector("table .btn-info", { timeout: 7000 });
    } catch (e) {
        if (e) {
            generalHelper.showDetails("There is no element to send");
            await browser.close();
            return;
        }
    }

    await page.$$eval('table .btn-info', elHandles => elHandles.forEach(el =>
        el.click()
    ))
}

const sendEmailOnPage = async (page) => {
    try {
        await page.waitForSelector("#sendEmail", { timeout: 4000 });
        await page.click("#sendEmail");
    } catch (e) {
        if (e) {
            generalHelper.showDetails("There is no element");
        }
    }
}

const iterateEachTab = async (browser) => {
    await delay(2000);
    var pages = await browser.pages();
    const pagesIndex = (await browser.pages()).map(p => p.url());
    if (pagesIndex.length < 3) {
        return
    }

    for (let index = 0; index < pagesIndex.length; index++) {
        await delay(2000);
        if (pages[index]) {
            (pages[index]).bringToFront();
            await sendEmailOnPage(pages[index]);
        }
    }
    await delay(10000);
    await browser.close();
}

function delay(time) {
    return new Promise(function (resolve) {
        setTimeout(resolve, time)
    });
}

async function startSendEmails(){   
    const browser = await initBrowser();
    const pageWithCookie = await setCookie(browser);
    await openAllPagesWithData(pageWithCookie, browser);
    if(browser.isConnected()){
        await iterateEachTab(browser);    
    }
    setTimeout(async ()=>{
        await startSendEmails();
    }, 300000); 
}

(async function index() {
    startSendEmails();
})()

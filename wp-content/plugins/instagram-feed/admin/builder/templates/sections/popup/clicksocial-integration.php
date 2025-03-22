<div class="sbi-integration-popup-modal sb-fs-boss sbi-fb-center-boss" v-if="viewsActive.clickSocialIntegrationModal">
    <div class="sbi-integration-popup sbi-fb-popup-inside">
        <div class="sbi-fb-popup-cls" @click.prevent.default="activateView('clickSocialIntegrationModal')">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#141B38" />
            </svg>
        </div>
        <div class="sbi-popup-content">
            <div class="sbi-popup-content-header">
                <img :src="clickSocialScreen.integrationLogo" alt="">
                <h3>{{clickSocialScreen.heading}}</h3>
                <p>{{clickSocialScreen.description}}</p>
            </div>
            <div class="sbi-popup-ua-integration-steps">
                <div class="sbi-popup-ua-integration-step">
                    <div class="sbi-left">
                        <h4>{{clickSocialScreen.installStep.title}}</h4>
                        <p>{{clickSocialScreen.installStep.description}}</p>
                        <button class="sbi-btn sbi-btn-install" @click.prevent.default="installclickSocialPlugin(clickSocialScreen.isPluginInstalled, clickSocialScreen.isPluginActive, clickSocialScreen.pluginDownloadPath, clickSocialScreen.clickSocialPlugin)" :disabled="disableClickSocialBtn">
                            <span v-html="clickSocialInstallBtnIcon()"></span>
                            <span v-html="clickSocialInstallBtnText()"></span>
                        </button>
                    </div>
                    <div class="sbi-step-image" v-html="clickSocialScreen.installStep.icon"></div>
                </div>
                <div class="sbi-popup-ua-integration-step sbi-popup-ua-setup-step">
                    <div class="sbi-left">
                        <h4>{{clickSocialScreen.setupStep.title}}</h4>
                        <p>{{clickSocialScreen.setupStep.description}}</p>
                        <button class="sbi-btn" :disabled="!enableClickSocialSetup" @click.prevent.default="setupclickSocialPlugin()">Set up Plugin</button>
                    </div>
                    <div class="sbi-step-image" v-html="clickSocialScreen.setupStep.icon"></div>
                </div>
            </div>
        </div>
    </div>
</div>
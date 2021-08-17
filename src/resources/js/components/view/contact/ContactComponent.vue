<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(editData) }} -->
        <div class="card card-body table-depart">
            <table
                class="table table-bordered"
                id="dataTable"
                width="100%"
                cellspacing="0"
            >
                <thead>
                    <tr>
                        <th>校區</th>
                        <th>班別</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(depart, index) in departmentIsFiltered"
                        :key="index"
                    >
                        <td>{{ index }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <b-button
                                    @click="department_change(item.id)"
                                    class="
                                        notification_btn
                                        notification_btn_sky
                                        notification_btn_text_white
                                    "
                                    size="is-small"
                                    v-for="(item, idx) in depart"
                                    :key="idx"
                                    >{{ item.name }}</b-button
                                >
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <b-steps
                v-model="activeStep"
                :animated="true"
                :rounded="true"
                :has-navigation="false"
                :icon-prev="prevIcon"
                :icon-next="nextIcon"
            >
                <b-step-item
                    step="1"
                    label="日期選擇"
                    :clickable="currentDepartment !== null"
                >
                    <div v-if="currentDepartment !== null">
                        <b-field horizontal label="選擇日期" class="w-50">
                            <b-datepicker
                                :date-formatter="
                                    (date) =>
                                        new Intl.DateTimeFormat('en-CA').format(
                                            date
                                        )
                                "
                                v-model="date"
                                :first-day-of-week="1"
                                placeholder="點擊"
                            >
                                <button
                                    class="button is-primary"
                                    @click="date = new Date()"
                                >
                                    <span>Today</span>
                                </button>
                            </b-datepicker>
                        </b-field>
                    </div>
                </b-step-item>

                <b-step-item step="2" label="出勤確認" :clickable="false">
                    <div class="scroll-contact-table mt-3">
                        <table
                            class="table table-bordered"
                            id="dataTable"
                            cellspacing="0"
                        >
                            <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>出勤狀況</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in attendanceData"
                                    :key="index"
                                >
                                    <td>
                                        <p class="ellipsis">
                                            {{ item.name }}
                                        </p>
                                    </td>

                                    <td>
                                        <p class="ellipsis">
                                            <b-select
                                                size="is-small"
                                                expanded
                                                :disabled="
                                                    date === null || isDisabled
                                                "
                                                v-model="item.leave"
                                                @input="editAttendance(item)"
                                            >
                                                <option
                                                    v-for="option in leaveType"
                                                    :value="option.id"
                                                    :key="option.id"
                                                >
                                                    {{ option.type }}
                                                </option>
                                            </b-select>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </b-step-item>

                <b-step-item
                    step="3"
                    label="聯絡簿編輯"
                    :clickable="false"
                    disabled
                >
                    <div>
                        <div v-if="currentDepartment !== null && date !== null">
                            <b-button
                                class="btn w-100"
                                type="button"
                                data-toggle="collapse"
                                data-target="#syncEdit"
                                aria-expanded="false"
                                aria-controls="syncEdit"
                            >
                                全編輯
                            </b-button>
                        </div>

                        <div class="collapse" id="syncEdit">
                            <div class="card card-body">
                                <div
                                    v-for="(item, index) in optionData"
                                    :key="index"
                                >
                                    <b-field
                                        horizontal
                                        :label="item.type | nameChange"
                                    >
                                        <div
                                            class="
                                                d-flex
                                                justify-content-arround
                                                flex-wrap
                                            "
                                        >
                                            <div
                                                v-for="(j, i) in item.options"
                                                :key="i"
                                                class="w20"
                                            >
                                                <input
                                                    type="checkbox"
                                                    v-model="
                                                        optionContent[item.type]
                                                    "
                                                    :value="j"
                                                    :disabled="
                                                        date === null ||
                                                        isDisabled
                                                    "
                                                />
                                                <span>{{ j }}</span>
                                            </div>
                                        </div>
                                    </b-field>
                                </div>
                                <b-field horizontal label="今日心情">
                                    <b-radio
                                        v-model="optionContent.mood"
                                        size="is-small"
                                        native-value="0"
                                        :disabled="date === null || isDisabled"
                                    >
                                        普通
                                    </b-radio>
                                    <b-radio
                                        v-model="optionContent.mood"
                                        size="is-small"
                                        native-value="1"
                                        :disabled="date === null || isDisabled"
                                    >
                                        愉快
                                    </b-radio>
                                    <b-radio
                                        v-model="optionContent.mood"
                                        size="is-small"
                                        native-value="2"
                                        :disabled="date === null || isDisabled"
                                    >
                                        生氣
                                    </b-radio>

                                    <!-- <span
                                        ><input
                                            type="radio"
                                            value="0"
                                            v-model="optionContent.mood"

                                        />普通</span
                                    > -->
                                    <!-- <span
                                        ><input
                                            type="radio"
                                            value="1"
                                            v-model="optionContent.mood"
                                        />愉快</span
                                    > -->
                                    <!-- <span
                                        ><input
                                            type="radio"
                                            value="2"
                                            v-model="optionContent.mood"
                                        />生氣</span
                                    > -->
                                </b-field>
                                <b-field horizontal label="學習日誌">
                                    <b-input
                                        type="textarea"
                                        maxlength="1000"
                                        v-model="optionContent.daily"
                                        :disabled="date === null || isDisabled"
                                    ></b-input>
                                </b-field>
                                <b-field
                                    horizontal
                                    label="檔案上傳"
                                    class="file is-primary"
                                    :class="{
                                        'has-name': !!optionContent.file,
                                    }"
                                >
                                    <div class="d-flex flex-column">
                                        <div>
                                            <b-upload
                                                v-model="optionContent.file"
                                                class="file-label"
                                                accept=".pdf,.docx,.doc,.pptx,.ppt,.xlsx,.xls"
                                                :disabled="
                                                    (optionContent.file ===
                                                        null &&
                                                        isCountLimit ===
                                                            true) ||
                                                    date === null ||
                                                    isDisabled
                                                "
                                            >
                                                <span class="file-cta">
                                                    <span class="file-label"
                                                        >(上傳pdf,doc,ppt,xls)</span
                                                    >
                                                </span>
                                                <div>
                                                    <span
                                                        class="file-name"
                                                        v-if="
                                                            optionContent.file
                                                        "
                                                    >
                                                        <a
                                                            class="
                                                                delete
                                                                is-small
                                                            "
                                                            type="button"
                                                            @click.prevent.self="
                                                                deleteDropFile
                                                            "
                                                        ></a>
                                                        {{
                                                            optionContent.file
                                                                .name
                                                        }}
                                                    </span>
                                                </div>
                                            </b-upload>
                                        </div>
                                        <div>
                                            <b-upload
                                                v-model="optionContent.file2"
                                                class="file-label"
                                                accept=".pdf,.docx,.doc,.pptx,.ppt,.xlsx,.xls"
                                                :disabled="
                                                    optionContent.file ===
                                                        null ||
                                                    (optionContent.file2 ===
                                                        null &&
                                                        isCountLimit ===
                                                            true) ||
                                                    date === null ||
                                                    isDisabled
                                                "
                                            >
                                                <span class="file-cta">
                                                    <span class="file-label"
                                                        >(上傳pdf,doc,ppt,xls)</span
                                                    >
                                                </span>
                                                <span
                                                    class="file-name"
                                                    v-if="optionContent.file2"
                                                >
                                                    <a
                                                        class="delete is-small"
                                                        type="button"
                                                        @click.prevent.self="
                                                            deleteDropFile2
                                                        "
                                                    ></a>
                                                    {{
                                                        optionContent.file2.name
                                                    }}
                                                </span>
                                            </b-upload>
                                        </div>
                                    </div>
                                </b-field>
                                <b-field
                                    horizontal
                                    label="圖片上傳"
                                    class="file is-primary"
                                    :class="{
                                        'has-name': !!optionContent.photo,
                                    }"
                                >
                                    <div class="d-flex flex-column">
                                        <div>
                                            <b-upload
                                                v-model="optionContent.photo"
                                                class="file-label"
                                                accept="image/jpeg"
                                                :disabled="
                                                    (optionContent.photo ===
                                                        null &&
                                                        isCountLimit ===
                                                            true) ||
                                                    date === null ||
                                                    isDisabled
                                                "
                                            >
                                                <span class="file-cta">
                                                    <span class="file-label"
                                                        >(上傳jpg)</span
                                                    >
                                                </span>
                                                <span
                                                    class="file-name"
                                                    v-if="optionContent.photo"
                                                >
                                                    <a
                                                        class="delete is-small"
                                                        type="button"
                                                        @click.prevent.self="
                                                            deleteDropPhoto
                                                        "
                                                    ></a>
                                                    {{
                                                        optionContent.photo.name
                                                    }}
                                                </span>
                                            </b-upload>
                                        </div>
                                        <div>
                                            <b-upload
                                                v-model="optionContent.photo2"
                                                class="file-label"
                                                :style="{
                                                    'background-color': true,
                                                }"
                                                accept="image/jpeg"
                                                :disabled="
                                                    optionContent.photo ===
                                                        null ||
                                                    (optionContent.photo2 ===
                                                        null &&
                                                        isCountLimit ===
                                                            true) ||
                                                    date === null ||
                                                    isDisabled
                                                "
                                            >
                                                <span class="file-cta">
                                                    <span class="file-label"
                                                        >(上傳jpg)</span
                                                    >
                                                </span>
                                                <span
                                                    class="file-name"
                                                    v-if="optionContent.photo2"
                                                >
                                                    <a
                                                        class="delete is-small"
                                                        type="button"
                                                        @click.prevent.self="
                                                            deleteDropPhoto
                                                        "
                                                    ></a>
                                                    {{
                                                        optionContent.photo2
                                                            .name
                                                    }}
                                                </span>
                                            </b-upload>
                                        </div>
                                    </div>
                                </b-field>
                                <b-field horizontal label="檔案上限">
                                    <limit-count
                                        :limit="2"
                                        :count="this.fileCount"
                                        @countLimit="isCountLimit = $event"
                                    ></limit-count>
                                </b-field>

                                <b-button
                                    @click="updateContacts"
                                    :disabled="date === null || isDisabled"
                                    >同步</b-button
                                >
                            </div>
                        </div>
                        <div class="scroll-contact-table mt-3">
                            <table
                                class="table table-bordered"
                                id="dataTable"
                                cellspacing="0"
                            >
                                <thead>
                                    <tr>
                                        <th>
                                            <input
                                                type="checkbox"
                                                ref="allCheckbox"
                                                @change="selectAll"
                                            />
                                        </th>

                                        <th>姓名</th>
                                        <th>身體狀況</th>
                                        <th>今日發回</th>
                                        <th>明日攜帶物品</th>
                                        <th>今日心情</th>
                                        <th>學習日誌</th>
                                        <th>給老師的話</th>
                                        <th>檔案</th>
                                        <th v-show="!isDisabled">編輯</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in contactsData"
                                        :key="index"
                                    >
                                        <td>
                                            <input
                                                ref="checkbox"
                                                type="checkbox"
                                                v-model="checkData"
                                                :value="item"
                                            />
                                        </td>
                                        <td>
                                            <p class="ellipsis">
                                                {{ item.name }}
                                            </p>
                                        </td>

                                        <td>
                                            <p class="ellipsis">
                                                {{
                                                    item.condition
                                                        | jsonToString
                                                }}
                                            </p>
                                        </td>

                                        <td>
                                            <p class="ellipsis">
                                                {{ item.return | jsonToString }}
                                            </p>
                                        </td>

                                        <td>
                                            <p class="ellipsis">
                                                {{ item.bring | jsonToString }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="ellipsis">
                                                {{ item.mood }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="ellipsis">
                                                {{ item.daily }}
                                            </p>
                                        </td>

                                        <td
                                            @click="
                                                showInfo(
                                                    item.to_teacher,
                                                    'string'
                                                )
                                            "
                                        >
                                            <a>
                                                <p class="ellipsis">
                                                    {{ item.to_teacher }}
                                                </p>
                                            </a>
                                        </td>
                                        <td>
                                            <div
                                                class="
                                                    d-flex
                                                    justify-content-around
                                                    align-items-center
                                                    flex-wrap
                                                "
                                            >
                                                <a
                                                    @click="
                                                        showInfo(
                                                            item.files,
                                                            'file'
                                                        )
                                                    "
                                                >
                                                    <svg
                                                        version="1.1"
                                                        id="圖層_1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        width="40"
                                                        height="40"
                                                        viewBox="0 0 44.3 41.8"
                                                        style="
                                                            enable-background: new
                                                                0 0 44.3 41.8;
                                                        "
                                                        xml:space="preserve"
                                                        :style="
                                                            getStyle(
                                                                item.files
                                                                    .length
                                                            )
                                                        "
                                                    >
                                                        <g>
                                                            <path
                                                                class="st0"
                                                                d="M8.4,12.2c0.1-0.3,0.1-0.6,0.2-0.8c0.5-1.4,1.9-2.2,3.4-2.1c0,0.5,0,1,0,1.5c0,0.8,0.2,1.5,0.7,2.1
		c0.7,0.9,1.5,1.3,2.6,1.3c2.3,0,4.5,0,6.8,0c2,0,3.4-1.4,3.5-3.5c0-0.5,0-0.9,0-1.4c0.8,0,1.6,0.1,2.3,0.6c1,0.7,1.4,1.7,1.4,2.9
		c0,2.6,0,5.2,0,7.9c0,0.1-0.1,0.3-0.1,0.3c-1.4,1.4-2.8,2.8-4.1,4.1c-0.2,0.2-0.3,0.1-0.5,0.1c-3,0-6,0-9.1,0c-0.8,0-1.7,0-2.5,0
		c-0.4,0-0.8,0.3-0.9,0.7c-0.1,0.4,0,0.8,0.4,1c0.2,0.1,0.5,0.2,0.7,0.2c3.2,0,6.3,0,9.5,0c0.1,0,0.2,0,0.3,0
		c-0.5,0.6-1.1,1.2-1.5,1.9c-0.4,0.7-0.4,1.6-0.7,2.4c-0.1,0-0.2,0-0.3,0c-2.9,0-5.7,0-8.6,0c-2,0-3.3-1.4-3.5-2.9c0,0,0,0,0-0.1
		C8.4,23,8.4,17.6,8.4,12.2z M18.9,21.2c-1.3,0-2.7,0-4,0c-0.6,0-1.2,0-1.8,0c-0.4,0-0.8,0.3-0.9,0.7c-0.1,0.4,0.1,0.8,0.4,1
		c0.2,0.1,0.4,0.1,0.5,0.1c3.8,0,7.7,0,11.5,0c0.1,0,0.1,0,0.2,0c0.4,0,0.7-0.3,0.8-0.7c0.1-0.6-0.3-1.1-1-1.1
		C22.7,21.2,20.8,21.2,18.9,21.2z M18.8,19.1c1.2,0,2.5,0,3.7,0c0.7,0,1.4,0,2.1,0c0.7,0,1.1-0.6,0.9-1.2c-0.1-0.4-0.5-0.7-1-0.7
		c-3.8,0-7.7,0-11.5,0c0,0-0.1,0-0.1,0c-0.4,0-0.7,0.3-0.8,0.6c-0.1,0.4,0,0.8,0.3,1c0.2,0.1,0.5,0.2,0.7,0.2
		C15.1,19.1,16.9,19.1,18.8,19.1z"
                                                            />
                                                            <path
                                                                class="st0"
                                                                d="M23.6,35.1c-0.7-0.4-0.8-0.6-0.6-1.4c0-0.2,0.1-0.4,0.1-0.6c0.1-0.9,0.3-1.7,0.4-2.6c0.1-0.3,0.2-0.6,0.4-0.9
		c3-3,6-6,9.1-9c0.7-0.7,1.5-0.9,2.3-0.5c0.2,0.1,0.4,0.3,0.5,0.4c0.4,0.3,0.7,0.7,1.1,1.1c0.5,0.5,0.9,0.9,1,1.6c0,0.2,0,0.4,0,0.6
		c-0.1,0.4-0.3,0.8-0.6,1.2c-3,3-6.1,6.1-9.1,9.1c-0.2,0.2-0.4,0.3-0.6,0.3c-0.5,0.1-0.9,0.2-1.4,0.3c-0.7,0.1-1.4,0.3-2.1,0.4
		C23.9,35.1,23.7,35.1,23.6,35.1z"
                                                            />
                                                            <path
                                                                class="st0"
                                                                d="M24.4,8.9C24.4,8.4,24,8,23.5,8c-0.2,0-0.4,0-0.6,0v0h-8.7c-0.5,0.1-0.8,0.4-0.9,1c0,0.6,0,1.2,0,1.8
		c0,1.3,1,2.1,2.2,2.1c2.2,0,4.5,0,6.7,0c1.1,0,2-0.9,2.1-2C24.4,10.3,24.4,9.6,24.4,8.9z"
                                                            />
                                                        </g>
                                                    </svg>
                                                </a>
                                                <a
                                                    @click="
                                                        showInfo(
                                                            item.photos,
                                                            'image'
                                                        )
                                                    "
                                                >
                                                    <svg
                                                        version="1.1"
                                                        id="圖層_1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        width="40"
                                                        height="40"
                                                        viewBox="0 0 44.3 41.8"
                                                        style="
                                                            enable-background: new
                                                                0 0 44.3 41.8;
                                                        "
                                                        xml:space="preserve"
                                                        :style="
                                                            getStyle(
                                                                item.photos
                                                                    .length
                                                            )
                                                        "
                                                    >
                                                        <g>
                                                            <circle
                                                                class="st0"
                                                                cx="24.2"
                                                                cy="20"
                                                                r="1.5"
                                                            />
                                                            <path
                                                                class="st0"
                                                                d="M25.2,27.9c-2.3,1.3-3.5,1.2-5.4-0.5c-1-0.9-2-1.9-3.2-2.7c-0.5-0.3-1.5-0.5-2-0.2c-1.7,1.1-3.4,2.2-4.8,3.6
		c-0.6,0.6-0.7,2.1-0.5,3.1c0.1,0.4,1.3,0.9,2.1,0.9c1.7,0.1,3.3,0,5,0c4.1,0,8.3,0,12.4,0c1.1,0,2.2,0,2.3-1.5c0.1-1.9,0-3.8,0-6.2
		C28.8,25.8,27,26.8,25.2,27.9z"
                                                            />
                                                            <path
                                                                class="st0"
                                                                d="M29.5,15c-6.3,0-12.6,0-18.8,0.1c-0.5,0-1.5,0.8-1.5,1.3c-0.1,3.1-0.1,6.2-0.1,9.4c0.5-0.1,0.6-0.1,0.7-0.2
		c1.1-0.8,2.2-1.5,3.2-2.3c2-1.4,3.2-1.3,5,0.2c1.1,0.9,2.1,1.8,3.1,2.7c0.9,0.8,1.7,1,2.8,0.3c2-1.2,4.1-2.3,6.1-3.6
		c0.5-0.3,1-1.1,1.1-1.8c0.2-1.4,0.1-2.9,0-4.3C31.1,15.7,30.7,15,29.5,15z M24.2,23.2c-1.8,0-3.2-1.5-3.2-3.2
		c0-1.8,1.5-3.2,3.2-3.2c1.8,0,3.2,1.5,3.2,3.2C27.4,21.8,26,23.2,24.2,23.2z"
                                                            />
                                                            <path
                                                                class="st0"
                                                                d="M33.3,11.6c-6-0.7-12-1.3-18-2c-1.6-0.2-2,0.6-2.2,2c-0.1,1.5,0.7,1.6,1.8,1.6c4.4,0,8.8,0,13.1,0
		c4.1,0,4.9,0.8,4.9,4.8c0,3.3,0,6.6,0,10c0.1,0,0.3,0,0.4,0c0.1-0.2,0.2-0.4,0.2-0.6c0.5-4.6,1.1-9.3,1.6-13.9
		C35.3,12.1,34.4,11.8,33.3,11.6z"
                                                            />
                                                        </g>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td v-show="!isDisabled">
                                            <b-button
                                                class="table-btn pl-0 pr-0"
                                            >
                                                <img
                                                    class="rounded-circle"
                                                    width="40px"
                                                    src="images/edit_icon.svg"
                                                    @click="
                                                        editContact(item.id)
                                                    "
                                                />
                                            </b-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </b-step-item>
            </b-steps>
            <div
                v-if="currentDepartment !== null && date !== null"
                class="ml-auto"
            >
                <b-button v-if="activeStep !== 0" @click="previousHandle">
                    上一步</b-button
                >
                <b-button
                    v-if="activeStep !== 2"
                    @click="nextHandle"
                    class="ml-1"
                >
                    下一步</b-button
                >
                <b-button v-show="checkData.length !== 0" class="ml-1">
                    <export-excel
                        class="btn btn-default"
                        :data="checkDataRename"
                        worksheet="My Worksheet"
                        name="filename.xls"
                    >
                        匯出
                    </export-excel></b-button
                >
            </div>
        </div>
        <b-modal :active.sync="isInfo" :width="640" scroll="clip">
            <contact-info
                :contactInfo="Info"
                :infoType="infoType"
            ></contact-info>
        </b-modal>
        <b-modal :active.sync="isEditContact" :width="640" scroll="clip">
            <EditContact
                :admin="admin_id"
                :editData="editData"
                :optionData="optionData"
                @refresh="getContacts(date)"
            ></EditContact>
        </b-modal>
        <!-- <div class="card-footer d-flex justify-content-center">
                <jw-pagination
                    :items="employeeDataSort"
                    :pageSize="12"
                    :labels="customLabels"
                    @changePage="onChangePage"
                ></jw-pagination>
            </div> -->

        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
import { renameKey } from "../../../function/index";
import moment from "moment";
import ContactInfo from "./components/ContactInfo";
import EditContact from "./modules/EditContact";
import LimitCount from "./components/LimitCount";
export default {
    components: {
        ContactInfo,
        EditContact,
        LimitCount,
    },
    data: function () {
        return {
            selected: new Date(),
            showWeekNumber: false,
            locale: undefined, // Browser locale
            isDisabled: false,
            isInfo: false,
            isEditContact: false,
            isLoading: false,
            school: null,
            optionData: [],
            optionContent: {
                condition: [],
                Return: [],
                bring: [],
                mood: null,
                daily: null,
                file: null,
                file2: null,
                photo: null,
                photo2: null,
            },
            departmentsData: [],
            currentDepartment: null,
            date: new Date(),
            contactsData: [],
            Info: null,
            editData: {},
            checkData: [],
            group_id: null,
            admin_id: null,
            teacher_id: null,
            infoType: "string",
            fileCount: 0,
            isCountLimit: false,
            activeStep: 0,
            isStepsClickable: true,
            prevIcon: "chevron-left",
            nextIcon: "chevron-right",
            attendanceData: [],
            leaveType: [
                {
                    id: 0,
                    type: "缺席",
                },
                {
                    id: 1,
                    type: "出席",
                },
                {
                    id: 2,
                    type: "病假",
                },
                {
                    id: 3,
                    type: "事假",
                },
            ],
        };
    },
    computed: {
        checkDataRename() {
            let contact = [];
            for (const element of this.checkData) {
                let data = renameKey(element, "name", "姓名");
                data["condition"] = element.condition
                    ? JSON.parse(element.condition).join(",")
                    : null;
                data = renameKey(data, "condition", "身體狀況");
                data["return"] = element.return
                    ? JSON.parse(element.return).join(",")
                    : null;
                data = renameKey(data, "return", "發回");
                data["bring"] = element.bring
                    ? JSON.parse(element.bring).join(",")
                    : null;
                data = renameKey(data, "bring", "明天帶");
                data = renameKey(data, "mood", "心情");
                data = renameKey(data, "daily", "教學日誌");
                delete data["id"];
                delete data["to_parent"];
                delete data["to_teacher"];
                delete data["files"];
                delete data["photos"];
                contact = [...contact, data];
            }
            return contact;
        },
        departmentIsFiltered: function () {
            if (this.teacher_id === null) {
                return this.departmentsData;
            } else {
                let teacher = this.teacher_id;
                let filtered = {};
                for (let [key, value] of Object.entries(this.departmentsData)) {
                    let result = value.filter(
                        (v) => v.supervisor_id === teacher
                    );
                    filtered[key] = result;
                }
                return filtered;
            }
        },
        contact_id: function () {
            let contact_id = [];
            for (const element of this.contactsData) {
                contact_id = [...contact_id, element.id];
            }
            return contact_id;
        },
    },
    watch: {
        currentDepartment(n, o) {
            if (this.date === null) {
                return;
            } else {
                let newOptionContent = {
                    condition: [],
                    Return: [],
                    bring: [],
                    mood: null,
                    daily: null,
                    file: null,
                    file2: null,
                    photo: null,
                    photo2: null,
                };

                this.optionContent = Object.assign({}, newOptionContent);

                if (
                    !(
                        moment(this.date).isAfter(
                            moment().add(-2, "days").format("YYYY-MM-DD")
                        ) && //前天、今天之後
                        moment(this.date).isBefore(
                            moment().add(1, "days").format("YYYY-MM-DD")
                        )
                    ) //前天、今天之前
                ) {
                    this.isDisabled = true;
                } else {
                    this.isDisabled = false;
                }
                this.getOption();
                this.getAttendance(this.date);
                this.getContacts(this.date);
            }
        },
        date(n, o) {
            let newOptionContent = {
                condition: [],
                Return: [],
                bring: [],
                mood: null,
                daily: null,
                file: null,
                file2: null,
                photo: null,
                photo2: null,
            };

            this.optionContent = Object.assign({}, newOptionContent);

            if (
                !(
                    moment(n).isAfter(
                        moment().add(-2, "days").format("YYYY-MM-DD")
                    ) && //前天、今天之後
                    moment(n).isBefore(
                        moment().add(1, "days").format("YYYY-MM-DD")
                    )
                ) //前天、今天之前
            ) {
                this.isDisabled = true;
            } else {
                this.isDisabled = false;
            }
            this.getOption();
            this.getAttendance(n);
            this.getContacts(n);
        },
        "optionContent.file"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "file");

                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "file");
            }
        },
        "optionContent.file2"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "file2");
                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "file2");
            }
        },
        "optionContent.photo"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "photo");
                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "photo");
            }
        },
        "optionContent.photo2"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "photo2");
                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "photo2");
            }
        },
        // "optionContent.condition"(n, o) {
        //     this.contactsData.map((item) => {
        //         for (let [key, value] of Object.entries(item)) {
        //             if (item[key] === null && key === "condition") {
        //                 item[key] = JSON.stringify(n);
        //                 // this.$set(item, "condition", n);
        //             }
        //             if (
        //                 item[key] === JSON.stringify(o) &&
        //                 key === "condition"
        //             ) {
        //                 item[key] = JSON.stringify(n);
        //                 // this.$set(item, "condition", n);
        //             }
        //         }
        //         return item;
        //     });
        // },
        // "optionContent.Return"(n, o) {
        //     this.contactsData.map((item) => {
        //         for (let [key, value] of Object.entries(item)) {
        //             if (item[key] === null && key === "return") {
        //                 item[key] = JSON.stringify(n);
        //             }
        //             if (item[key] === JSON.stringify(o) && key === "return") {
        //                 item[key] = JSON.stringify(n);
        //             }
        //         }
        //         return item;
        //     });
        // },
        // "optionContent.bring"(n, o) {
        //     this.contactsData.map((item) => {
        //         for (let [key, value] of Object.entries(item)) {
        //             if (item[key] === null && key === "bring") {
        //                 item[key] = JSON.stringify(n);
        //             }
        //             if (item[key] === JSON.stringify(o) && key === "bring") {
        //                 item[key] = JSON.stringify(n);
        //             }
        //         }
        //         return item;
        //     });
        // },
        // condition(n, o) {
        //     this.contactsData.map((item) => {
        //         for (let [key, value] of Object.entries(item)) {
        //             if (item[key] === null && key === "condition") {
        //                 item[key] = n;
        //                 // this.$set(item, "condition", n);
        //             }
        //             if (item[key] === o && key === "condition") {
        //                 item[key] = n;
        //                 // this.$set(item, "condition", n);
        //             }
        //         }
        //         return item;
        //     });
        // },
        // daily(n, o) {
        //     this.contactsData.map((item) => {
        //         for (let [key, value] of Object.entries(item)) {
        //             if (item[key] === null && key === "daily") {
        //                 item[key] = n;
        //             }
        //             if (item[key] === o && key === "daily") {
        //                 item[key] = n;
        //             }
        //         }
        //         return item;
        //     });
        // },
        // Return(n, o) {
        //     this.contactsData.map((item) => {
        //         for (let [key, value] of Object.entries(item)) {
        //             if (item[key] === null && key === "return") {
        //                 item[key] = n;
        //             }
        //             if (item[key] === o && key === "return") {
        //                 item[key] = n;
        //             }
        //         }
        //         return item;
        //     });
        // },
        // bring(n, o) {
        //     this.contactsData.map((item) => {
        //         for (let [key, value] of Object.entries(item)) {
        //             if (item[key] === null && key === "bring") {
        //                 item[key] = n;
        //             }
        //             if (item[key] === o && key === "bring") {
        //                 item[key] = n;
        //             }
        //         }
        //         return item;
        //     });
        // },
    },
    filters: {
        jsonToString(json) {
            if (json === null) {
                return null;
            } else if (typeof json === "string") {
                return JSON.parse(json).toString();
            } else {
                return json.toString();
            }
        },
        nameChange(type) {
            if (type === "condition") {
                return "身體狀況";
            } else if (type === "Return") {
                return "今天發回";
            } else if (type === "bring") {
                return "明日攜帶物品";
            } else {
                return null;
            }
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        //sessionStorage　出來是string
        if (sessionStorage.teacher_id !== "null") {
            this.teacher_id = Number(sessionStorage.teacher_id);
        }
        this.school = sessionStorage.school;
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
    },
    mounted() {
        this.getDepartments();
    },
    methods: {
        previousHandle() {
            if (this.activeStep > 0) {
                this.activeStep = this.activeStep - 1;
            }
        },
        nextHandle() {
            //閱覽過去紀錄直接跳過出勤確認
            if (
                this.activeStep === 0 &&
                !(
                    moment(this.date).isAfter(
                        moment().add(-2, "days").format("YYYY-MM-DD")
                    ) && //前天、今天之後
                    moment(this.date).isBefore(
                        moment().add(1, "days").format("YYYY-MM-DD")
                    )
                ) //前天、今天之前
            ) {
                this.activeStep = 2;
            }
            if (this.activeStep < 2) {
                this.activeStep = this.activeStep + 1;
            }
        },
        selectAll() {
            console.log(this.$refs.allCheckbox.checked);
            if (this.$refs.checkbox) {
                if (this.$refs.allCheckbox.checked == true) {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        editContact(id) {
            this.isLoading = true;
            axios
                .get("contact/getEdit", { params: { id: id } })
                .then((response) => {
                    if (response.data.result == true) {
                        this.editData = response.data.data;
                    }
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });

            this.isEditContact = true;
        },
        showInfo(item, type) {
            this.infoType = type;
            this.Info = item;
            this.isInfo = true;
        },
        department_change(id) {
            this.currentDepartment = id;
        },
        getDepartments() {
            this.isLoading = true;
            axios
                .get("department/index", { params: { school_id: this.school } })
                .then((response) => {
                    this.departmentsData = response.data;
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
        },
        getContacts(date) {
            this.isLoading = true;
            axios
                .get("contact/index", {
                    params: {
                        department_id: this.currentDepartment,
                        date: date,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.contactsData = response.data.data;
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.checkData = [];
                    this.isLoading = false;
                });
        },
        updateContacts() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin_id) {
                formData.append("admin_id", JSON.stringify(this.admin_id));
            }
            if (this.currentDepartment) {
                formData.append(
                    "department_id",
                    JSON.stringify(this.currentDepartment)
                );
            }
            if (this.contact_id) {
                formData.append(
                    "contact_id_arr",
                    JSON.stringify(this.contact_id)
                );
            }
            if (this.optionContent) {
                for (const [key, value] of Object.entries(this.optionContent)) {
                    if (
                        key === "condition" ||
                        key === "Return" ||
                        key === "bring"
                    ) {
                        formData.append(key, JSON.stringify(value));
                    } else if (value !== null) {
                        formData.append(key, value);
                    }
                }
            }
            axios
                .post("contact/updateSync", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    let newOptionContent = {
                        condition: [],
                        Return: [],
                        bring: [],
                        mood: null,
                        daily: null,
                        file: null,
                        file2: null,
                        photo: null,
                        photo2: null,
                    };
                    this.isLoading = false;
                    this.optionContent = Object.assign({}, newOptionContent);
                    this.getContacts(this.date);
                });
        },
        getAttendance(date) {
            this.isLoading = true;
            axios
                .get("contact/attendance/index", {
                    params: {
                        department_id: this.currentDepartment,
                        date: date,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.attendanceData = response.data.data;
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        editAttendance(item) {
            console.log("change:", item);
            this.isLoading = true;
            axios
                .post("contact/attendance/edit", {
                    attendance_id: item.attendance_id,
                    leave: item.leave,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        getOption() {
            this.isLoading = true;
            axios
                .get("option/indexOption", {
                    params: {
                        type: "Contact",
                        department_id: this.currentDepartment,
                    },
                })
                .then((response) => {
                    this.optionData = response.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        getStyle(length) {
            switch (length) {
                case 0:
                    return { fill: "#cccccc" };
                default:
                    return { fill: "#008778" };
            }
        },
        checkFile(file, key) {
            let result = true;
            const SIZE_LIMIT = 5242880; // 5MB
            if (file.size > SIZE_LIMIT) {
                this.$buefy.toast.open({
                    message: file.name + " 超過上限5MB",
                    type: "is-danger",
                    queue: false,
                });
                this.optionContent[key] = null;
            }
        },
        deleteDropFile() {
            this.optionContent.file = null;
        },
        deleteDropFile2() {
            this.optionContent.file2 = null;
        },
        deleteDropPhoto() {
            this.optionContent.photo = null;
        },
        deleteDropPhoto2() {
            this.optionContent.photo2 = null;
        },
    },
};
</script>

<style lang="scss" scoped>
// .scroll-table {
//     overflow-y: scroll;
//     height: 15vh;
//     .table th {
//         position: sticky;
//         top: -1px;
//         z-index: 1;
//     }
// }
.scroll-contact-table {
    overflow-y: scroll;
    max-height: 60vh;
    table {
        border: 1px solid #dbdbdb;
        min-width: 100%;

        vertical-align: top;
    }
    table.table th {
        position: sticky;
        top: -1px;
        z-index: 1;
        white-space: nowrap;
        border-width: 1px;
    }
    table.table td {
        // white-space: normal !important;
        word-wrap: break-word;
        border-width: 1px;
        max-width: 100px;
    }
}
.ellipsis {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    white-space: normal;
}
.float-button {
    position: fixed;
    bottom: 3vh;
    right: 3vw;
}
.w20 {
    width: 20%;
}
.delete-button {
    position: absolute;
    top: 7px;
    left: 5px;
}
</style>

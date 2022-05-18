<!--
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
 -->

<template>
  <div class="orangehrm-background-container orangehrm-save-candidate-page">
    <div class="orangehrm-card-container">
      <oxd-text tag="h6" class="orangehrm-main-title">
        {{ $t('recruitment.candidate_profile') }}
      </oxd-text>
      <oxd-divider />
      <oxd-form :loading="isLoading" @submitValid="onSave">
        <oxd-form-row>
          <oxd-grid :cols="1" class="orangehrm-full-width-grid">
            <oxd-grid-item>
              <full-name-input
                v-model:first-name="profile.firstName"
                v-model:middle-name="profile.middleName"
                v-model:last-name="profile.lastName"
                :rules="rules"
                required
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
        <oxd-form-row>
          <oxd-grid :cols="3" class="orangehrm-full-width-grid">
            <oxd-grid-item>
              <vacancy-dropdown
                v-model="profile.vacancy"
                :label="$t('recruitment.job_vacancy')"
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
        <oxd-form-row>
          <oxd-grid :cols="3" class="orangehrm-full-width-grid">
            <oxd-grid-item>
              <oxd-input-field
                v-model="profile.email"
                :label="$t('general.email')"
                :placeholder="$t('general.type_here')"
                :rules="rules.email"
                required
              />
            </oxd-grid-item>
            <oxd-grid-item>
              <oxd-input-field
                v-model="profile.contactNumber"
                :label="$t('recruitment.contact_number')"
                :placeholder="$t('general.type_here')"
                :rules="rules.contactNumber"
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
        <oxd-form-row>
          <oxd-grid :cols="3" class="orangehrm-full-width-grid">
            <oxd-grid-item>
              <file-upload-input
                v-model:newFile="profile.newResume"
                v-model:method="profile.method"
                :label="$t('recruitment.resume')"
                :button-label="$t('general.browse')"
                :file="profile.oldResume"
                :rules="rules.resume"
                url="recruitment/resume"
                :hint="$t('general.accept_custom_format_file')"
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
        <oxd-form-row>
          <oxd-grid :cols="3" class="orangehrm-full-width-grid">
            <oxd-grid-item
              class="orangehrm-save-candidate-page --span-column-2"
            >
              <oxd-input-field
                v-model="profile.keywords"
                :label="$t('general.keywords')"
                :placeholder="$t('general.type_here')"
                :rules="rules.keywords"
              />
            </oxd-grid-item>
            <oxd-grid-item>
              <date-input
                v-model="profile.applicationDate"
                :label="$t('recruitment.date_of_application')"
                :rules="rules.applicationDate"
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
        <oxd-form-row>
          <oxd-grid :cols="3" class="orangehrm-full-width-grid">
            <oxd-grid-item
              class="orangehrm-save-candidate-page --span-column-2"
            >
              <oxd-input-field
                v-model="profile.notes"
                :label="$t('general.notes')"
                type="textarea"
                :placeholder="$t('general.type_here')"
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
        <oxd-form-row>
          <oxd-grid :cols="3" class="orangehrm-full-width-grid">
            <oxd-grid-item
              class="orangehrm-save-candidate-page-full-width orangehrm-save-candidate-page-grid-checkbox"
            >
              <oxd-input-field
                v-model="profile.keep"
                type="checkbox"
                :label="$t('recruitment.content_to_keep_data')"
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
        <oxd-divider />
        <required-text></required-text>
        <oxd-form-actions>
          <oxd-button display-type="ghost" :label="$t('general.cancel')" />
          <submit-button :label="$t('general.save')" />
        </oxd-form-actions>
      </oxd-form>
    </div>
  </div>
</template>

<script>
import FullNameInput from '@/orangehrmPimPlugin/components/FullNameInput';
import {APIService} from '@/core/util/services/api.service';
import {
  shouldNotExceedCharLength,
  required,
  validDateFormat,
  validPhoneNumberFormat,
  validEmailFormat,
  maxFileSize,
  validFileTypes,
} from '@/core/util/validation/rules';
import VacancyDropdown from '@/orangehrmRecruitmentPlugin/components/VacancyDropdown';
import FileUploadInput from '@/core/components/inputs/FileUploadInput';
import DateInput from '@/core/components/inputs/DateInput';
import {navigate} from '@/core/util/helper/navigation';
export default {
  name: 'CandidateProfile',
  components: {
    DateInput,
    'vacancy-dropdown': VacancyDropdown,
    'file-upload-input': FileUploadInput,
    'full-name-input': FullNameInput,
  },
  props: {
    candidateId: {
      type: Number,
      required: true,
    },
    allowedFileTypes: {
      type: Array,
      required: true,
    },
    maxFileSize: {
      type: Number,
      required: true,
    },
  },
  setup(props) {
    const http = new APIService(
      'https://c81c3149-4936-41d9-ab3d-e25f1bff2934.mock.pstmn.io',
      `/recruitment/candidate/${props.candidateId}`,
    );

    return {
      http,
    };
  },
  data() {
    return {
      isLoading: false,
      profile: {
        firstName: '',
        middleName: '',
        lastName: '',
        email: '',
        contactNumber: '',
        oldResume: '',
        notes: '',
        keywords: '',
        newResume: null,
        vacancy: null,
        resume: null,
        method: 'keepCurrent',
        applicationDate: null,
        keep: null,
      },
      rules: {
        firstName: [required, shouldNotExceedCharLength(30)],
        lastName: [required, shouldNotExceedCharLength(30)],
        middleName: [shouldNotExceedCharLength(30)],
        email: [required, validEmailFormat, shouldNotExceedCharLength(50)],
        contactNumber: [validPhoneNumberFormat, shouldNotExceedCharLength(25)],
        keywords: [shouldNotExceedCharLength(250)],
        applicationDate: [validDateFormat()],
        resume: [
          maxFileSize(1024 * 1024),
          validFileTypes(this.allowedFileTypes),
        ],
      },
    };
  },

  beforeMount() {
    this.isLoading = true;
    this.http.getAll().then(({data: {data}}) => {
      const {resume, candidate, ...rest} = data;
      this.profile.oldResume = resume?.id ? resume : null;
      this.profile.newResume = null;
      this.profile.firstName = candidate.firstName;
      this.profile.middleName = candidate.middleName;
      this.profile.lastName = candidate.lastName;
      this.profile.method = 'keepCurrent';
      this.profile.vacancy = data.vacancy;
      this.profile = {
        ...this.profile,
        ...rest,
      };
      this.isLoading = false;
    });
  },
  methods: {
    onSave() {
      this.isLoading = true;
      this.http
        .update(this.candidateId, this.profile)
        .then(() => {
          return this.$toast.updateSuccess();
        })
        .then(() => {
          navigate(`/recruitment/addCandidate/${this.candidateId}`);
        });
    },
  },
};
</script>

<style scoped lang="scss">
.orangehrm-save-candidate-page {
  &-grid-checkbox {
    .oxd-input-group {
      flex-direction: row-reverse;
      justify-content: flex-end;
    }
  }
}
</style>
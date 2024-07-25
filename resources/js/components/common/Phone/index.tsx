import React, { ChangeEvent, FocusEvent, useState } from 'react';
import { postHttpRequest } from "./../../../axios";
import PhoneI from './types/PhoneI';
import PostDataI from './types/PostDataI';
import ResponseDataI from './types/ResponseDataI';

/**
 * Phone Component.
 *
 * All rights Reseverd | Arhamsoft Pvt @2023
 * 
 * @param {Object} props - Component props.
 * @param {string} props.name - Name attribute for the input element.
 * @param {string} props.placeholder - Placeholder text for the input element.
 * @param {boolean} props.required - Indicates if the input is required.
 * @param {string} props.value - value for the input element.
 * @param {string} [props.className] - Optional to dynamiclly add the classs.
 * @param {string} [props.label] - dynamiclly add label to the element.
 * @param {string} [props.id] - dynamiclly add id to the element.
 * @param {ChangeEvent<HTMLInputElement>} [props.onChange] - callback.
 * @param {string} [props.divClass] - dynamiclly add id to the parnt element.
 * @param {boolean} [props.hasError] - dynamiclly pass the true/false value.
 * @param {string} [props.errorMessage] - dynamiclly pass the error message.
 * @param {ChangeEvent<HTMLInputElement>} [props.setHasError] - callback
 * 
 * @returns {JSX.Element} - JSX representation of the component.
 */

const Phone: React.FC<PhoneI> = ({ name, placeholder, required, value, className, label, id, onChange, divClass, hasError, errorMessage, setHasError }) => {

    const [inputValue, setInputValue] = useState<string>('');
    const [phoneData, setPhoneData] = useState<ResponseDataI | null>(null);
    const [isValid, setIsValid] = useState<boolean>(false);
    const [blurred, setBlurred] = useState<number>(2);

    /**
     * Handles changes to the input value.
     *
     * @param {ChangeEvent<HTMLInputElement>} event - The change event.
     * @returns {void}
     */
    const handleInputChange = (event: ChangeEvent<HTMLInputElement>): void => {
        setBlurred(0);
        const value = event.target.value;
        setInputValue(value);
        if (onChange) {
            onChange(event);
            if (value && value.length >= 11) {
                setHasError(false);
                return;
            }
            else if (required) {
                setHasError(true);
            }
        }
    };
    /**
     * Handles Submit to the input value.
     *
     * @returns {void}
     */
    const blur = async (event: FocusEvent<HTMLInputElement>): Promise<void> => {

        setBlurred(1);
        const value = event.target.value;
        if (!value || value.length < 11) {
            setHasError(true);
            setIsValid(false);
            return;
        }
        let data = await fetchPhoneValidity();
        setPhoneData(data as ResponseDataI);
        if (!data.isValid) {
            setHasError(true);
            setIsValid(false);
        }
        setIsValid(true);
    };

    /**
     * Handles Submit to the input value.
     *
     * @returns {Promise<ResponseDataI>}
     */
    const fetchPhoneValidity = async (): Promise<ResponseDataI> => {
        return new Promise(async (resolve, reject) => {
            try {
                const payload: PostDataI = {
                    "phoneNumber": `${value}`
                };
                await postHttpRequest('api/numbers/verify', payload)
                    .then(function (res) {
                        resolve(res.data);
                    });
            } catch (error) {
                reject(error);
            }
        });
    }

    return (
        <>
            <div className={divClass}>
                {label && <label htmlFor={id}>{label} {required && <span>*</span>}</label>}
                <input
                    type="tel"
                    id={id}
                    name={name}
                    placeholder={placeholder ?? 'Enter phone'}
                    value={value || ''}
                    className={hasError ? 'has-error ' + className : isValid ? 'is-valid ' + className : className}
                    onChange={handleInputChange}
                    onBlur={blur}
                />
                {(hasError && blurred > 0) && <p className='error'>{errorMessage}</p>}
            </div>
        </>
    )

};

export default Phone;
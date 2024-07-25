import React, { ChangeEvent, useEffect, useState } from 'react';
import { formatDateByDMY, parseDateComponentsIntoDMY } from './../../utils/dateUtils';
interface DOBDropdownProps {
  name: string;
  id?: string;
  className?: string;
  value?: string;
  label?: string;
  required?: boolean;
  onChange: (event: ChangeEvent<HTMLSelectElement>) => void;
  divClass?: string;
  hasError?: boolean;
  errorMessage?: string;
  setHasError: (isValid: boolean) => void;
  dobFormat?: 'DD/MM/YYYY' | 'YYYY/MM/DD' | 'MM/DD/YYYY' | 'DD-MM-YYYY' | 'YYYY-MM-DD' | 'MM-DD-YYYY';
}

/**
 * DOB.
 *
 * 
 * @param {Object} props - Component props.
 * @param {string} props.name - Name attribute for the input element.
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
 * @param {string} [props.dobFormat]
 * 
 * @returns {JSX.Element} - JSX representation of the component.
 */

function DOB(props: DOBDropdownProps) {

  let [day, setDay] = useState<string>('');
  let [month, setMonth] = useState<string>('');
  let [year, setYear] = useState<string>('');
  let [dob, setDOB] = useState<string>('');
  let [isValidD, setIsValidD] = useState<boolean>(false);
  let [isValidM, setIsValidM] = useState<boolean>(false);
  let [isValidY, setIsValidY] = useState<boolean>(false);
  const days = [];
  for (let i = 1; i < 32; i++) (i < 10) ? days.push("0" + i) : days.push(i);
  const months = [];
  for (let i = 1; i < 13; i++) (i < 10) ? months.push("0" + i) : months.push(i);
  const years = [];
  for (let i = 2023; i > 1919; i--) years.push(i);
  const { name, id = 'id-' + props.name, className = 'input-field', value, required, onChange, label, divClass = 'form-group', hasError, errorMessage = 'Please select date of birth', setHasError, dobFormat = 'DD-MM-YYYY' } = props;


  /**
   * SideEffects.
   */
  useEffect(function () {
    if (day && month && year) {
      let dateofbirth = formatDateByDMY(day, month, year, dobFormat);
      setDOB(dateofbirth);
      setHasError(false);
      if (onChange) {
        const updatedEvent = {
          target: {
            name,
            value: dateofbirth,
          },
        } as ChangeEvent<HTMLSelectElement>;
        onChange(updatedEvent);
      }
    } else {
      setDOB('');
    }
  }, [day, month, year]);

  useEffect(() => {
    if (value) {
      let [parsedDay, parsedMonth, parsedYear] = parseDateComponentsIntoDMY(value, dobFormat);
      setDay(parsedDay);
      setMonth(parsedMonth);
      setYear(parsedYear);
    }
  }, [value]);

  /**
   * Handles DayChange.
   *
   * @param {ChangeEvent<HTMLInputElement>} event - The onchange event.
   * @returns {void}
   */
  const handleDayChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    const selectedDay = event.target.value;
    setDay(selectedDay);
    setIsValidD(true);
  };  
  
  /**
  * Handles MonthChange.
  *
  * @param {ChangeEvent<HTMLInputElement>} event - The onchange event.
  * @returns {void}
  */
  const handleMonthChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    const selectedMonth = event.target.value;
    setMonth(selectedMonth);
    setIsValidM(true);
  };


  /**
  * Handles YearChange.
  *
  * @param {ChangeEvent<HTMLInputElement>} event - The onchange event.
  * @returns {void}
  */
  const handleYearChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    const selectedYear = event.target.value;
    setYear(selectedYear);
    setIsValidY(true);
  };

  return (
    <div className={divClass}>
      {label && <label>{label} {required && <span>*</span>}</label>}
      <div className='dateOfBirth_wrap'>
      <select id={id + 'Day'} value={day} onChange={handleDayChange} className={`${day === '' && hasError ? 'has-error ' : ''}${isValidD ? 'is-valid ' : ''}${className}`} name={name + 'Day'} required>
        <option value="" hidden disabled>Day</option>
        {days.map((d) => (
          <option key={d} value={d}>
            {d}
          </option>
        ))}
      </select>
      <select id={id + 'Month'} value={month} onChange={handleMonthChange} className={`${month === '' && hasError ? 'has-error ' : ''}${isValidM ? 'is-valid ' : ''}${className}`} name={name + 'Month'} required>
        <option value="" hidden disabled>Month</option>
        {months.map((m) => (
          <option key={m} value={m}>
            {m}
          </option>
        ))}
      </select>
      <select id={id + 'Year'} value={year} onChange={handleYearChange} className={`${year === '' && hasError ? 'has-error ' : ''}${isValidY ? 'is-valid ' : ''}${className}`} name={name + 'Year'} required>
        <option value="" hidden disabled>Year</option>
        {years.map((y) => (
          <option key={y} value={y}>
            {y}
          </option>
        ))}
      </select>
      </div>
      {hasError && <p className='error'>{errorMessage}</p>}
    </div>
  );
};

export default DOB;
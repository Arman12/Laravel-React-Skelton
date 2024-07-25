export function formatDateByDMY(day: string, month: string, year: string, dobFormat: string): string {
    let dateOfBirth = '';
  
    switch (dobFormat) {
      case 'DD/MM/YYYY':
        dateOfBirth = `${day}/${month}/${year}`;
        break;
      case 'YYYY/MM/DD':
        dateOfBirth = `${year}/${month}/${day}`;
        break;
      case 'MM/DD/YYYY':
        dateOfBirth = `${month}/${day}/${year}`;
        break;
      case 'DD-MM-YYYY':
        dateOfBirth = `${day}-${month}-${year}`;
        break;
      case 'YYYY-MM-DD':
        dateOfBirth = `${year}-${month}-${day}`;
        break;
      case 'MM-DD-YYYY':
        dateOfBirth = `${month}-${day}-${year}`;
        break;
      default:
        break;
    }
  
    return dateOfBirth;
  }

  export function parseDateComponentsIntoDMY(value: string, dobFormat: string): [string, string, string] {
    const dateComponents = value.split(/[-/]/);
    let parsedDay = '';
    let parsedMonth = '';
    let parsedYear = '';
  
    switch (dobFormat) {
      case 'DD/MM/YYYY':
        [parsedDay, parsedMonth, parsedYear] = dateComponents;
        break;
      case 'YYYY/MM/DD':
        [parsedYear, parsedMonth, parsedDay] = dateComponents;
        break;
      case 'MM/DD/YYYY':
        [parsedMonth, parsedDay, parsedYear] = dateComponents;
        break;
      case 'DD-MM-YYYY':
        [parsedDay, parsedMonth, parsedYear] = dateComponents;
        break;
      case 'YYYY-MM-DD':
        [parsedYear, parsedMonth, parsedDay] = dateComponents;
        break;
      case 'MM-DD-YYYY':
        [parsedMonth, parsedDay, parsedYear] = dateComponents;
        break;
      default:
        break;
    }
  
    return [parsedDay, parsedMonth, parsedYear];
  }
  
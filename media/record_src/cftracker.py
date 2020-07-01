from datetime import datetime
import requests

def get_today_accept_count(user):
    url = 'https://codeforces.com/api/user.status?handle='+user
    response = requests.get(url)
    parsed_response = response.json()
    if parsed_response['status'] == 'FAILED':
        print('[FAILED]: ', contestant, ': Not Found')
        return -1
    today = datetime.toordinal(datetime.now())
    sub_count = 0
    for submission in parsed_response['result']:
        sub_day = datetime.fromtimestamp(submission['creationTimeSeconds'])
        sub_day = datetime.toordinal(sub_day)
        if sub_day == today and submission['verdict'] == 'OK':
            sub_count += 1
    return sub_count

if __name__ == '__main__':
    contestants = ('arcturus5340',
                   'FreeKILL',
                   'Nidavellir',
                   'Virohn',
                   'W1adimir')

    for contestant in contestants:
        print(get_today_accept_count(contestant))

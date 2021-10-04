import boto3

s3 = boto3.resource('s3')
obj = s3.Object(bucket_name='megabucketboy', key='key.txt')
response = obj.get()
data = response['Body'].read()
dataStr = data.decode('UTF-8')
print(dataStr)